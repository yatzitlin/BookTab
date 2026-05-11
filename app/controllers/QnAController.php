<?php

require_once __DIR__ . '/BaseController.php';

class QnAController extends BaseController {
    private $qnaModel;

    public function __construct($dbConnection) {
        parent::__construct($dbConnection);
        $this->qnaModel = $this->loadModel('QnAModel');
    }

    public function getCategories() {
        return $this->qnaModel->getAllCategories();
    }

    public function getQuestions($category = '', $page = 1, $itemsPerPage = 10) {
        $page = max(1, (int)$page);
        $itemsPerPage = max(1, (int)$itemsPerPage);

        $unansweredCount = $this->qnaModel->countUnansweredQuestions($category);
        $answeredCount = $this->qnaModel->countQuestionsWithAnswers($category);
        $totalItems = $unansweredCount + $answeredCount;
        $totalPages = ceil($totalItems / $itemsPerPage);

        $fetchSize = $itemsPerPage * 3;
        $unanswered = $this->qnaModel->getUnansweredQuestions($category, 1, $fetchSize);
        $answered = $this->qnaModel->getQuestionsWithAnswers($category, 1, $fetchSize);

        $items = array_merge($unanswered, $answered);
        usort($items, function($a, $b) {
            $timeA = strtotime($a['ngay_tao'] ?? 0);
            $timeB = strtotime($b['ngay_tao'] ?? 0);
            return $timeB - $timeA;
        });

        $offset = ($page - 1) * $itemsPerPage;
        $paginatedItems = array_slice($items, $offset, $itemsPerPage);

        $questionIds = [];
        $answerIds = [];
        foreach ($paginatedItems as $it) {
            $questionIds[] = $it['ma_cau_hoi'];
            if (!empty($it['ma_cau_tra_loi'])) $answerIds[] = $it['ma_cau_tra_loi'];
        }

        $qImages = $this->qnaModel->getImagesForQuestions($questionIds);
        $aImages = $this->qnaModel->getImagesForAnswers($answerIds);

        foreach ($paginatedItems as &$it) {
            $qid = $it['ma_cau_hoi'];
            $aid = $it['ma_cau_tra_loi'] ?? null;
            $it['images'] = $qImages[$qid] ?? [];
            $it['answer_images'] = ($aid && isset($aImages[$aid])) ? $aImages[$aid] : [];
        }

        return [
            'items' => $paginatedItems,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'itemsPerPage' => $itemsPerPage
        ];
    }

    public function getFaqItems($category = '', $page = 1, $itemsPerPage = 10) {
        $page = max(1, (int)$page);
        $itemsPerPage = max(1, (int)$itemsPerPage);

        $totalItems = $this->qnaModel->countPublicFaqItems($category);
        $totalPages = ceil($totalItems / $itemsPerPage);

        $items = $this->qnaModel->getPublicFaqItems($category, $page, $itemsPerPage);

        $questionIds = [];
        $answerIds = [];
        foreach ($items as $it) {
            $questionIds[] = $it['ma_cau_hoi'];
            if (!empty($it['ma_cau_tra_loi'])) $answerIds[] = $it['ma_cau_tra_loi'];
        }

        $qImages = $this->qnaModel->getImagesForQuestions($questionIds);
        $aImages = $this->qnaModel->getImagesForAnswers($answerIds);

        foreach ($items as &$it) {
            $qid = $it['ma_cau_hoi'];
            $aid = $it['ma_cau_tra_loi'] ?? null;
            $it['images'] = $qImages[$qid] ?? [];
            $it['answer_images'] = ($aid && isset($aImages[$aid])) ? $aImages[$aid] : [];
        }

        return [
            'items' => $items,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'itemsPerPage' => $itemsPerPage
        ];
    }

    public function createQuestion($tenCauHoi, $maLoai, $userId) {
        $tenCauHoi = trim($tenCauHoi);

        if (empty($tenCauHoi) || strlen($tenCauHoi) < 10 || strlen($tenCauHoi) > 255) {
            return ['error' => 'Nội dung câu hỏi không hợp lệ (10-255 ký tự).'];
        }

        if ((int)$maLoai <= 0) {
            return ['error' => 'Chủ đề không hợp lệ.'];
        }

        if ((int)$userId <= 0) {
            return ['error' => 'Người dùng không hợp lệ.'];
        }

        $insertId = $this->qnaModel->createQuestion($tenCauHoi, (int)$maLoai, (int)$userId);
        if (!$insertId) {
            return ['error' => 'Không thể tạo câu hỏi. Vui lòng thử lại.'];
        }

        if (!empty($_FILES['images']) && is_array($_FILES['images']['name'])) {
            $files = $_FILES['images'];
            $maxFiles = 5;
            $allowedExt = ['jpg','jpeg','png','webp'];
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
            $maxSize = 3 * 1024 * 1024;

            $publicDir = dirname(__DIR__,2) . '/public/upload/qna';
            if (!is_dir($publicDir)) {
                @mkdir($publicDir, 0755, true);
            }

            $uploadedCount = 0;
            for ($i = 0; $i < count($files['name']) && $uploadedCount < $maxFiles; $i++) {
                if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;

                $origName = $files['name'][$i];
                $tmp = $files['tmp_name'][$i];
                $size = $files['size'][$i];

                $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
                if (!in_array($ext, $allowedExt)) continue;

                if ($size > $maxSize) continue;

                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $tmp);
                finfo_close($finfo);
                if (!in_array($mime, $allowedMimes)) continue;

                $safe = bin2hex(random_bytes(8)) . '_' . time() . '_' . $uploadedCount . '.' . $ext;
                $destPath = $publicDir . '/' . $safe;

                if (move_uploaded_file($tmp, $destPath)) {
                    $webPath = 'public/upload/qna/' . $safe;
                    $imageId = $this->qnaModel->createImage($webPath, $origName, $uploadedCount);
                    if ($imageId) {
                        $this->qnaModel->linkImageToQuestion($insertId, $imageId, $uploadedCount);
                        $uploadedCount++;
                    }
                }
            }
        }

        return ['success' => true, 'id' => $insertId];
    }

    // ============================================================
    // My Questions (logged-in user)
    // ============================================================

    public function getMyQuestions($userId, $page = 1, $perPage = 10) {
        $totalItems = $this->qnaModel->countMyQuestions($userId);
        $totalPages = ceil($totalItems / $perPage);
        $items = $this->qnaModel->getMyQuestions($userId, $page, $perPage);

        $questionIds = [];
        $answerIds = [];
        foreach ($items as $it) {
            $questionIds[] = $it['ma_cau_hoi'];
            if (!empty($it['ma_cau_tra_loi'])) $answerIds[] = $it['ma_cau_tra_loi'];
        }

        $qImages = $this->qnaModel->getImagesForQuestions($questionIds);
        $aImages = $this->qnaModel->getImagesForAnswers($answerIds);

        foreach ($items as &$it) {
            $qid = $it['ma_cau_hoi'];
            $aid = $it['ma_cau_tra_loi'] ?? null;
            $it['images'] = $qImages[$qid] ?? [];
            $it['answer_images'] = ($aid && isset($aImages[$aid])) ? $aImages[$aid] : [];
        }

        return [
            'items' => $items,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'itemsPerPage' => $perPage
        ];
    }

    // ============================================================
    // Admin methods
    // ============================================================

    public function adminGetQuestions($page = 1, $perPage = 15, $category = 0) {
        $totalItems = $this->qnaModel->countAllQuestionsAdmin($category);
        $totalPages = ceil($totalItems / $perPage);
        $items = $this->qnaModel->getAllQuestionsAdmin($page, $perPage, $category);

        return [
            'items' => $items,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'itemsPerPage' => $perPage
        ];
    }

    public function adminGetQuestionDetail($id) {
        $question = $this->qnaModel->getQuestionDetailAdmin($id);
        if (!$question) return null;

        $question['images'] = [];
        $question['answer_images'] = [];

        $qImages = $this->qnaModel->getImagesForQuestions([$id]);
        $question['images'] = $qImages[$id] ?? [];

        if (!empty($question['ma_cau_tra_loi'])) {
            $aImages = $this->qnaModel->getImagesForAnswers([$question['ma_cau_tra_loi']]);
            $question['answer_images'] = $aImages[$question['ma_cau_tra_loi']] ?? [];
        }

        return $question;
    }

    public function adminCreateAnswer($cauHoiId, $noiDung, $adminUserId) {
        $noiDung = trim($noiDung);
        if (empty($noiDung)) {
            return ['error' => 'Nội dung trả lời không được để trống.'];
        }
        try {
            $result = $this->qnaModel->createAnswer($cauHoiId, $adminUserId, $noiDung);
            return ['success' => true, 'id' => $result];
        } catch (\Exception $e) {
            return ['error' => 'Lỗi: ' . $e->getMessage()];
        }
    }

    public function adminUpdateAnswer($cauTraLoiId, $noiDung) {
        $noiDung = trim($noiDung);
        if (empty($noiDung)) {
            return ['error' => 'Nội dung trả lời không được để trống.'];
        }
        $this->qnaModel->updateAnswer($cauTraLoiId, $noiDung);
        return ['success' => true];
    }

    public function adminDeleteQuestion($id) {
        $id = (int)$id;

        // 1. Lấy tất cả ảnh liên quan (câu hỏi + câu trả lời)
        $images = $this->qnaModel->getImagePathsByQuestionId($id);

        // 2. Xóa file vật lý trên server
        if (!empty($images)) {
            $basePath = dirname(__DIR__, 2); // thư mục gốc BookTab/
            foreach ($images as $img) {
                $filePath = $basePath . '/' . ltrim($img['url_anh'], '/');
                if (is_file($filePath)) {
                    @unlink($filePath);
                }
            }
            // 3. Xóa record ảnh trong DB
            $anhIds = array_column($images, 'ma_anh');
            $this->qnaModel->deleteImageRecords($anhIds);
        }

        // 4. Xóa câu hỏi (cascade sẽ xóa cau_tra_loi, anh_cau_hoi, anh_cau_tra_loi nếu có FK)
        $this->qnaModel->deleteQuestion($id);
        return ['success' => true];
    }

    public function adminDeleteAnswer($id) {
        $this->qnaModel->deleteAnswer($id);
        return ['success' => true];
    }

    public function adminUpdateStatus($id, $trangThai) {
        $this->qnaModel->updateQuestionStatus($id, $trangThai);
        return ['success' => true];
    }

    public function adminUnhideQuestion($id) {
        $hasAnswer = $this->qnaModel->questionHasAnswer($id);
        $newStatus = $hasAnswer ? 'da_tra_loi' : 'chua_tra_loi';
        $this->qnaModel->updateQuestionStatus($id, $newStatus);
        return ['success' => true];
    }

    public function adminCreateFaq($tenCauHoi, $maLoai, $adminUserId, $noiDung) {
        $tenCauHoi = trim($tenCauHoi);
        $noiDung = trim($noiDung);

        if (empty($tenCauHoi) || strlen($tenCauHoi) < 10 || strlen($tenCauHoi) > 255) {
            return ['error' => 'Nội dung câu hỏi không hợp lệ (10-255 ký tự).'];
        }
        if ((int)$maLoai <= 0) {
            return ['error' => 'Chủ đề không hợp lệ.'];
        }
        if (empty($noiDung)) {
            return ['error' => 'Nội dung trả lời không được để trống.'];
        }

        $insertId = $this->qnaModel->createQuestion($tenCauHoi, (int)$maLoai, (int)$adminUserId, 'Yes');
        if (!$insertId) {
            return ['error' => 'Không thể tạo FAQ.'];
        }

        $this->qnaModel->createAnswer($insertId, (int)$adminUserId, $noiDung);
        return ['success' => true, 'id' => $insertId];
    }

    public function adminGetFaqItems($page = 1, $perPage = 15, $category = 0) {
        $totalItems = $this->qnaModel->countFaqItemsAdmin($category);
        $totalPages = ceil($totalItems / $perPage);
        $items = $this->qnaModel->getFaqItemsAdmin($page, $perPage, $category);

        return [
            'items' => $items,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'itemsPerPage' => $perPage
        ];
    }

    public function adminCreateCategory($tenLoai, $soThuTu = 0) {
        $tenLoai = trim($tenLoai);
        if (empty($tenLoai)) {
            return ['error' => 'Tên chủ đề không được để trống.'];
        }
        $existing = $this->qnaModel->getCategoryByName($tenLoai);
        if ($existing) {
            return ['error' => 'Chủ đề này đã tồn tại.'];
        }
        $result = $this->qnaModel->createCategory($tenLoai, $soThuTu);
        if (!$result) {
            return ['error' => 'Không thể tạo chủ đề.'];
        }
        return ['success' => true];
    }

    public function adminUpdateCategory($id, $newTenLoai, $soThuTu) {
        $id = (int)$id;
        $newTenLoai = trim($newTenLoai);
        if (empty($newTenLoai)) {
            return ['error' => 'Tên chủ đề không được để trống.'];
        }
        $existing = $this->qnaModel->getCategoryByName($newTenLoai);
        if ($existing && (int)$existing['ma_loai'] !== $id) {
            return ['error' => 'Chủ đề này đã tồn tại.'];
        }
        $this->qnaModel->updateCategory($id, $newTenLoai, (int)$soThuTu);
        return ['success' => true];
    }

    public function adminDeleteCategory($id) {
        $this->qnaModel->deleteCategory((int)$id);
        return ['success' => true];
    }

    public function adminReorderCategories(array $order) {
        $ids = array_map('intval', $order);
        $ids = array_filter($ids, fn($id) => $id > 0);
        if (empty($ids)) {
            return ['error' => 'Dữ liệu sắp xếp không hợp lệ.'];
        }
        $this->qnaModel->reorderCategories($ids);
        return ['success' => true];
    }
}
