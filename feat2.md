# BookTab Feature Summary (QnA, FAQ, QnAAsk, About)

Tài liệu này tóm tắt logic cuối cùng của các màn hình QnA, FAQ, QnAAsk và About, kèm các đoạn code chính.

## 1) QnA (Trang hỏi đáp)

### Mục tiêu
- Hiển thị cả câu hỏi đã có trả lời và chưa có trả lời.
- Sắp xếp chung theo thời gian tạo mới nhất.
- Có phân trang, lọc theo chủ đề.
- Hiển thị ảnh câu hỏi + ảnh câu trả lời.
- Có phóng to ảnh khi click.
- Hiển thị người trả lời trong ô trả lời và canh phải cuối ô nội dung.

### Luồng xử lý chính
- Route `page=qna` trong `public/index.php` gọi `QnAController::getQuestions(...)`.
- Controller lấy 2 tập dữ liệu:
  - `getUnansweredQuestions(...)`
  - `getQuestionsWithAnswers(...)`
- Gộp mảng, sort theo `ngay_tao DESC`, rồi cắt theo trang.
- Nạp ảnh liên quan qua:
  - `getImagesForQuestions(...)`
  - `getImagesForAnswers(...)`
- View `QnA.php` render danh sách, hiển thị khối answer nếu có.

### Snippet chính: merge + sort + paginate (Controller)
```php
public function getQuestions($categoryId = 0, $page = 1, $itemsPerPage = 10) {
    $page = max(1, (int)$page);
    $itemsPerPage = max(1, (int)$itemsPerPage);

    $unansweredCount = $this->qnaModel->countUnansweredQuestions($categoryId);
    $answeredCount = $this->qnaModel->countQuestionsWithAnswers($categoryId);
    $totalItems = $unansweredCount + $answeredCount;
    $totalPages = ceil($totalItems / $itemsPerPage);

    $fetchSize = $itemsPerPage * 3;
    $unanswered = $this->qnaModel->getUnansweredQuestions($categoryId, 1, $fetchSize);
    $answered = $this->qnaModel->getQuestionsWithAnswers($categoryId, 1, $fetchSize);

    $items = array_merge($unanswered, $answered);
    usort($items, function($a, $b) {
        $timeA = strtotime($a['ngay_tao'] ?? 0);
        $timeB = strtotime($b['ngay_tao'] ?? 0);
        return $timeB - $timeA;
    });

    $offset = ($page - 1) * $itemsPerPage;
    $paginatedItems = array_slice($items, $offset, $itemsPerPage);

    // attach question_images + answer_images
    // ...

    return [
        'items' => $paginatedItems,
        'currentPage' => $page,
        'totalPages' => $totalPages,
        'totalItems' => $totalItems,
        'itemsPerPage' => $itemsPerPage
    ];
}
```

### Snippet chính: người trả lời nằm trong answer box, canh phải (View)
```php
<?php if (!empty($item['cau_tra_loi'])): ?>
    <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 text-gray-700 leading-7 mb-3 flex flex-col gap-4">
        <div class="answer-content">
            <?php echo $item['cau_tra_loi']; ?>
        </div>

        <?php if (!empty($item['admin_ho_va_ten_dem']) || !empty($item['admin_ten'])): ?>
            <div class="mt-auto flex justify-end">
                <p class="text-sm text-gray-500 text-right">
                    <span class="block font-semibold text-gray-700">Người trả lời</span>
                    <?php echo htmlspecialchars(trim(($item['admin_ho_va_ten_dem'] ?? '') . ' ' . ($item['admin_ten'] ?? '')), ENT_QUOTES, 'UTF-8'); ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
```

### Snippet chính: lightbox phóng to ảnh (View JS)
```js
const triggers = document.querySelectorAll('.image-zoom-trigger');
const lightbox = document.createElement('div');
lightbox.className = 'fixed inset-0 z-50 hidden items-center justify-center bg-black/80 p-4';
// ...
const open = (src, alt) => {
  image.src = src;
  image.alt = alt || '';
  caption.textContent = alt || '';
  lightbox.classList.remove('hidden');
  lightbox.classList.add('flex');
  document.body.classList.add('overflow-hidden');
};
```

## 2) FAQ (Trang câu hỏi thường gặp)

### Mục tiêu
- Chỉ hiển thị các câu hỏi đã có trả lời và thuộc nhóm FAQ public.
- Có lọc theo chủ đề + phân trang.
- Hiển thị ảnh câu hỏi/ảnh câu trả lời + phóng to ảnh.
- **Không hiển thị người trả lời**.

### Luồng xử lý chính
- Route `page=qna&tab=faq` trong `public/index.php`.
- Gọi `QnAController::getFaqItems(...)`.
- Dữ liệu FAQ lấy từ model `getPublicFaqItems(...)` + `countPublicFaqItems(...)`.
- View `FAQ.php` render câu hỏi + nội dung trả lời + ảnh, không render block tên admin.

### Snippet chính: FAQ data path (Controller)
```php
public function getFaqItems($categoryId = 0, $page = 1, $itemsPerPage = 10) {
    $page = max(1, (int)$page);
    $itemsPerPage = max(1, (int)$itemsPerPage);

    $totalItems = $this->qnaModel->countPublicFaqItems($categoryId);
    $totalPages = ceil($totalItems / $itemsPerPage);
    $items = $this->qnaModel->getPublicFaqItems($categoryId, $page, $itemsPerPage);

    // attach images for question + answer
    // ...

    return [
        'items' => $items,
        'currentPage' => $page,
        'totalPages' => $totalPages,
        'totalItems' => $totalItems,
        'itemsPerPage' => $itemsPerPage
    ];
}
```

### Snippet chính: FAQ không hiển thị người trả lời (View)
```php
<div class="bg-gray-50 border border-gray-100 rounded-lg p-4 text-gray-700 leading-7 mb-3 flex flex-col gap-4">
    <div class="answer-content">
        <?php echo $item['cau_tra_loi'] ?? ''; ?>
    </div>

    <?php if (!empty($item['answer_images'])): ?>
        <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-3">
            <!-- render ảnh trả lời -->
        </div>
    <?php endif; ?>
</div>
```

## 3) QnAAsk (Trang gửi câu hỏi)

### Mục tiêu
- Chỉ user đã đăng nhập mới được hỏi.
- Bảo vệ CSRF.
- Validate server-side + client-side.
- Hỗ trợ upload nhiều ảnh (max 5, max 3MB/ảnh, JPEG/PNG/WebP).
- Lưu ảnh vào thư mục cố định: `public/upload/qna/questions`.
- Lưu metadata ảnh vào bảng `anh`, map sang câu hỏi qua `anh_cau_hoi`.

### Luồng xử lý chính
- `public/index.php?page=qna_ask`:
  - GET: tải danh mục chủ đề, render form.
  - POST: kiểm tra login, CSRF, validate text/chủ đề/ảnh.
  - Nếu hợp lệ: gọi `QnAController::createQuestion(...)`, set flash success, redirect về QnA.
- Form có drag-drop + preview ảnh + chặn lỗi client trước khi submit.

### Snippet chính: route + validation POST (Index)
```php
case 'qna_ask':
    if (!isset($_SESSION['userid'])) {
        header('Location: ' . BASE_URL . '/public/index.php?page=login&error=qna_login_required');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['qna_form_error'] = 'CSRF token không hợp lệ. Vui lòng thử lại.';
            header('Location: ' . BASE_URL . '/public/index.php?page=qna_ask');
            exit;
        }

        // Validate question + category + images
        // ...

        $qnaController->createQuestion($tenCauHoi, $maLoai, (int) $_SESSION['userid']);
        $_SESSION['qna_form_success'] = 'Câu hỏi của bạn đã được ghi nhận...';
        header('Location: ' . BASE_URL . '/public/index.php?page=qna');
        exit;
    }
```

### Snippet chính: upload ảnh và lưu DB (Controller)
```php
if (!empty($_FILES['images']) && is_array($_FILES['images']['name'])) {
    $maxFiles = 5;
    $allowedExt = ['jpg','jpeg','png','webp'];
    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
    $maxSize = 3 * 1024 * 1024;

    $publicDir = dirname(__DIR__,2) . '/public/upload/qna/questions';
    if (!is_dir($publicDir)) {
        @mkdir($publicDir, 0755, true);
    }

    $uploadedCount = 0;
    for ($i = 0; $i < count($files['name']) && $uploadedCount < $maxFiles; $i++) {
        // validate ext/size/mime
        // move_uploaded_file(...)
        // createImage(...)
        // linkImageToQuestion(...)
    }
}
```

### Snippet chính: client-side multiple image validation (View JS)
```js
const allowedTypes = ['image/png', 'image/jpeg', 'image/webp'];
const maxFiles = 5;
const maxSize = 3 * 1024 * 1024;

if (files.length > maxFiles) {
  errors.push('Không được tải lên quá 5 ảnh');
}
files.forEach(function(file) {
  if (!allowedTypes.includes(file.type)) {
    errors.push(`Ảnh '${file.name}' không phải định dạng hỗ trợ...`);
  }
  if (file.size > maxSize) {
    errors.push(`Ảnh '${file.name}' vượt quá 3MB.`);
  }
});
```

## 4) About (Trang giới thiệu)

### Mục tiêu
- Lấy các section giới thiệu đang active từ DB.
- Hero lấy section đầu tiên.
- Các section còn lại hiển thị dạng grid card.

### Luồng xử lý chính
- Route `page=about` trong `public/index.php` tạo `AboutController`.
- `AboutController::getActiveSections()` gọi model.
- `AboutModel::getActiveSections()` query bảng `gioi_thieu` theo `trang_thai = 'active'`, order theo `so_thu_tu`.
- View `About.php`:
  - hero = phần tử đầu.
  - phần còn lại = danh sách card.

### Snippet chính: model query About
```php
public function getActiveSections() {
    $sql = "SELECT ma_section, tieu_de, mo_ta_ngan, noi_dung, hinh_anh_url, so_thu_tu
            FROM gioi_thieu
            WHERE trang_thai = 'active'
            ORDER BY so_thu_tu ASC, ma_section ASC";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
```

## 5) Chủ đề QnA/FAQ hiện tại

Danh mục chủ đề hiện được seed trong dữ liệu gồm:
1. Đặt hàng
2. Thanh toán
3. Vận chuyển
4. Sản phẩm & sách
5. Tài khoản & đơn hàng
6. Ưu đãi & khuyến mãi
7. Khác

### Snippet seed chủ đề
```sql
INSERT INTO `loai_cau_hoi` (`ma_loai`, `ten_loai`) VALUES
(1, 'Đặt hàng'),
(2, 'Thanh toán'),
(3, 'Vận chuyển'),
(4, 'Sản phẩm & sách'),
(5, 'Tài khoản & đơn hàng'),
(6, 'Ưu đãi & khuyến mãi'),
(7, 'Khác');
```

## 6) Tổng kết ngắn
- QnA: hợp nhất 2 nguồn dữ liệu (đã trả lời/chưa trả lời), có ảnh và lightbox, có người trả lời nằm trong answer box.
- FAQ: chỉ hiển thị nội dung FAQ đã trả lời, vẫn có ảnh/lightbox, không hiển thị người trả lời.
- QnAAsk: bảo vệ CSRF + validate 2 lớp + upload nhiều ảnh theo rule cố định.
- About: render động theo dữ liệu section active từ bảng `gioi_thieu`.
