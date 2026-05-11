<?php
require_once __DIR__ . '/BaseController.php';

class AdminNewsController extends BaseController {
    private $newsModel;
    private $categoryModel;

    public function __construct($dbConnection) {
        parent::__construct($dbConnection);
        $this->newsModel = $this->loadModel('NewsModel');
        $this->categoryModel = $this->loadModel('NewsCategoryModel');
    }

    // Danh sách bài viết
    public function index() {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $cat_id = isset($_GET['cat_id']) ? (int)$_GET['cat_id'] : 0; // Lấy ID danh mục từ URL
        
        if (!empty($keyword)) {
            $newsList = $this->newsModel->searchNews($keyword);
        } else {
            $newsList = $this->newsModel->getAllNews();
        }

        // Tag danh mục trên datatable
        if ($cat_id > 0) {
            $newsList = array_filter($newsList, function($news) use ($cat_id) {
                return $news['ma_loai'] == $cat_id;
            });
        }

        $categories = $this->categoryModel->getAllCategories();

        require_once __DIR__ . '/../views/admin/adminLayout.php'; 
    }

    // Thêm bài viết mới
    public function create() {
        $categories = $this->categoryModel->getAllCategories();

        require_once __DIR__ . '/../views/admin/adminLayout.php'; 
    }

    // Lưu thay đổi
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra trạng thái
            $trang_thai_form = $_POST['trang_thai'] ?? 'ban_nhap';
            $trang_thai_hop_le = ['ban_nhap', 'da_dang', 'luu_tru'];
            if (!in_array($trang_thai_form, $trang_thai_hop_le)) {
                $trang_thai_form = 'ban_nhap';
            }

            // Thêm loại bài viết mới
            $ma_loai = $_POST['ma_loai'] ?? 1;
            if ($ma_loai === 'new' && !empty($_POST['ten_loai_moi'])) {
                $ten_loai_moi = trim($_POST['ten_loai_moi']);
                $slug_loai = $this->createSlug($ten_loai_moi);
                
                $ma_loai_moi = $this->categoryModel->insertAndGetId([
                    'ten_loai' => $ten_loai_moi,
                    'slug' => $slug_loai,
                    'trang_thai' => 'active'
                ]);
                
                $ma_loai = $ma_loai_moi ? $ma_loai_moi : 1; 
            }

            // Bài viết
            $tieu_de = trim($_POST['tieu_de'] ?? '');
            // Tạo slug cho bài viết
            $slug = $this->createSlug($tieu_de); 
            $finalSlug = $slug;

            // Kiểm tra và chèn số ngẫu nhiên nếu trùng
            while ($this->newsModel->isSlugExists($finalSlug)) {
                $randomNumber = rand(0, 999);
                $finalSlug = $slug . '-' . $randomNumber;
            }

            $data = [
                'tieu_de' => $tieu_de,
                'tom_tat' => trim($_POST['tom_tat'] ?? ''),
                'noi_dung' => $_POST['noi_dung'] ?? '',
                'thumbnail_url' => $this->handleUpload(), 
                'trang_thai' => $trang_thai_form,
                'slug' => $finalSlug,
                'ma_loai' => $ma_loai,
                'administrator_userid' => $_SESSION['userid']
            ];

            // Lưu
            if ($this->newsModel->insert($data)) {
                header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=news');
                exit;
            } else {
                echo "Có lỗi xảy ra khi lưu bài viết!";
            }
        }
    }

    // Chỉnh sửa
    public function edit() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id > 0) {
            $newsToEdit = $this->newsModel->getById($id);
            
            if ($newsToEdit) {
                // Quan trọng: Phải lấy lại danh sách bài viết và danh mục để trang nền vẫn hiện bảng dữ liệu
                $newsList = $this->newsModel->getAllNews();
                $categories = $this->categoryModel->getAllCategories();
                
                // "Đánh lừa" Layout load lại file AdminNews.php làm nền
                $_GET['admin_action'] = 'news'; 
                
                // Gọi layout
                require_once __DIR__ . '/../views/admin/adminLayout.php'; 
                return;
            }
        }
        header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=news');
        exit;
    }

    // Lưu lại bản đã chỉnh sửa
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Hỗ trợ lấy id từ POST nếu không có trong query string (trong một số trường hợp modal gửi form mà thiếu id trên URL)
            $id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['id']) ? (int)$_POST['id'] : 0);
            $newsOld = $this->newsModel->getById($id);
            
            if (!$newsOld) {
                // Nếu id không hợp lệ -> chuyển về danh sách và hiển thị trạng thái lỗi (tránh trang trắng)
                header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=news&status=error');
                exit;
            }

            // Xử lý Trạng thái và Danh mục
            $trang_thai_form = $_POST['trang_thai'] ?? 'ban_nhap';
            $ma_loai = $_POST['ma_loai'] ?? $newsOld['ma_loai'];
            
            if ($ma_loai === 'new' && !empty($_POST['ten_loai_moi'])) {
                $ten_loai_moi = trim($_POST['ten_loai_moi']);
                $slug_loai = $this->createSlug($ten_loai_moi);
                $ma_loai_moi = $this->categoryModel->insertAndGetId([
                    'ten_loai' => $ten_loai_moi, 'slug' => $slug_loai, 'trang_thai' => 'active'
                ]);
                $ma_loai = $ma_loai_moi ? $ma_loai_moi : 1; 
            }

            // Xử lý Slug (Cho phép giữ nguyên tên cũ mà không bị báo trùng)
            $tieu_de = trim($_POST['tieu_de'] ?? '');
            $slug = $this->createSlug($tieu_de); 
            $finalSlug = $slug;

            while ($this->newsModel->isSlugExists($finalSlug, $id)) {
                $randomNumber = rand(0, 999);
                $finalSlug = $slug . '-' . $randomNumber;
            }

            // Xử lý Ảnh Thumbnail
            // Nếu có file được chọn và upload thành công thì lấy file mới
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                $thumbnail_url = $this->handleUpload();
            } else {
                // Nếu không chọn file, giữ nguyên đường dẫn ảnh cũ
                $thumbnail_url = $newsOld['thumbnail_url'];
            }

            $data = [
                'tieu_de' => $tieu_de,
                'tom_tat' => trim($_POST['tom_tat'] ?? ''),
                'noi_dung' => $_POST['noi_dung'] ?? '',
                'thumbnail_url' => $thumbnail_url,
                'trang_thai' => $trang_thai_form,
                'slug' => $finalSlug,
                'ma_loai' => $ma_loai
            ];

            if ($this->newsModel->update($id, $data)) {
                header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=news&status=success');
                exit;
            } else {
                header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=news&status=error');
                exit;
            }
        }

        // Nếu không phải POST thì chuyển về trang danh sách
        header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=news');
        exit;
    }

    // Xóa bài viết
    public function delete() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        
        if ($id && $this->newsModel->delete($id)) {
            header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=news');
            exit;
        }
    }

    // Helper function
    
    // Tự động tạo slug URL từ Tiêu đề (VD: "Tin Mới" -> "tin-moi")
    private function createSlug($str) {
        // Chuyển đổi Tiếng Việt có dấu thành không dấu
        $unicode = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd'=>'đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D'=>'Đ',
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
        
        // Đổi chữ có dấu thành không dấu
        foreach($unicode as $nonUnicode => $uni){
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        
        // Chuyển thành chữ thường
        $str = mb_strtolower(trim($str), 'UTF-8');
        // Thay thế các ký tự không phải chữ cái và số bằng dấu gạch ngang
        $str = preg_replace('/[^a-z0-9\-]/', '-', $str);
        // Xóa các dấu gạch ngang dư thừa liên tiếp
        $str = preg_replace('/-+/', '-', $str);
        
        return trim($str, '-');
    }

    // upload ảnh lên server
    private function processFileUpload($fileInputName, $role, $slug = '') {
        if (!isset($_FILES[$fileInputName]) || $_FILES[$fileInputName]['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $folderTime = date('Y-m-d');
        $uploadDir = __DIR__ . '/../../public/upload/news/' . $folderTime . '/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileExtension = pathinfo($_FILES[$fileInputName]['name'], PATHINFO_EXTENSION);
        
        // Nếu slug rỗng (Admin chưa gõ tiêu đề)
        $safeSlug = empty($slug) ? 'chua-co-tieu-de' : $slug;
        
        // Tạo tên theo định dạng: [vaitro]-[slug]-[thoigian].[duoifile]
        $fileName = $role . '-' . $safeSlug . '-' . time() . '-' . rand(100, 999) . '.' . $fileExtension;
        
        $destination = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $destination)) {
            return 'upload/news/' . $folderTime . '/' . $fileName;
        }
        
        return false;
    }

    // Upload Thumbnail (dùng lúc nhấn "Lưu bài viết")
    private function handleUpload() {
        // Vì lúc này Admin đã submit form -> có thể lấy thẳng tiêu đề để tạo slug
        $slug = isset($_POST['tieu_de']) ? $this->createSlug($_POST['tieu_de']) : '';
        
        // Gọi hàm upload ảnh lên server
        $result = $this->processFileUpload('thumbnail', 'thumbnail', $slug);
        
        if ($result) {
            return $result;
        }
        return 'upload/news/default-thumbnail.avif';
    }

    // Insert ảnh vào phần nội dung trong TinyMCE (Chạy ngầm bằng AJAX)
    public function uploadImage() {
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        header('Content-Type: application/json; charset=utf-8');
        
        // Lấy tiêu đề được gửi kèm từ JS của TinyMCE (nếu có)
        $slug = isset($_POST['tieu_de_ajax']) ? $this->createSlug($_POST['tieu_de_ajax']) : '';

        // Gọi hàm upload ảnh lên server
        $result = $this->processFileUpload('file', 'image', $slug);
        
        if ($result) {
            $url = BASE_URL . '/public/' . $result;
            echo json_encode(['location' => $url]);
            exit;
        }
        
        header("HTTP/1.1 500 Server Error");
        echo json_encode(['error' => 'Lỗi upload ảnh ở server']);
        exit;
    }
}
?>