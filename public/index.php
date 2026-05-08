<?php
define('BASE_URL', 'http://localhost/BookTab');

require_once __DIR__ . '/../app/controllers/AuthController.php';
// Enable display errors for development troubleshooting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/core/Database.php'; 
require_once __DIR__ . '/../app/controllers/AboutController.php';
require_once __DIR__ . '/../app/controllers/QnAController.php';

$database = new Database();
$dbConnection = $database->connect();

// Cấu hình cài đặt cookie session bảo mật
AuthController::configureSessionSecurity();

session_start();

// Tạo một thực thể AuthController duy nhất để xử lý session và yêu cầu.
$authController = new AuthController($dbConnection);

// Kiểm tra session timeout trước khi xử lý
$authController->checkSessionTimeout();

// Tạo token chống CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Kiểm tra action
$action = isset($_GET['action']) ? $_GET['action'] : null;

if ($action === 'register') {
    $authController->handleRegister();
    exit;
} elseif ($action === 'login') {
    $authController->handleLogin();
    exit;
} elseif ($action === 'logout') {
    $authController->logout();
    exit;
}

// Nếu không có action, kiểm tra page
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Kiểm tra quyền admin
if ($page === 'admin' || $page === 'admin_dashboard') {
    if (!isset($_SESSION['userid']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrator') {
        header('Location: ' . BASE_URL . '/public/index.php?page=login&error=unauthorized');
        exit;
    }
}

if ($page === 'login') {
    $authController->showLoginForm();
    exit;
} elseif ($page === 'register') {
    $authController->showRegisterForm();
    exit;
}

$qnaController = new QnAController($dbConnection);

// Routing cho các page thường
switch ($page) {
    case 'home':
        $view_content = '../app/views/pages/Home.php';
        $pageTitle = 'Trang chủ';
        break;
    case 'about':
        $aboutController = new AboutController($dbConnection);
        $aboutSections = $aboutController->getActiveSections();
        $view_content = '../app/views/pages/About.php';
        $pageTitle = 'Giới thiệu';
        break;
    case 'products':
        $view_content = '../app/views/pages/Products.php';
        $pageTitle = 'Sản phẩm';
        break;
    case 'news':
        $view_content = '../app/views/pages/News.php';
        $pageTitle = 'Tin tức';
        break;
    case 'qna':
        $qnaTab = isset($_GET['tab']) ? trim($_GET['tab']) : 'qna';
        $selectedCategory = isset($_GET['category']) ? (int) $_GET['category'] : 0;
        $currentPage = isset($_GET['qna_page']) ? max(1, (int) $_GET['qna_page']) : 1;
        $itemsPerPage = 10;
        $qnaCategories = $qnaController->getCategories();
        if ($qnaTab === 'faq') {
            $faqData = $qnaController->getFaqItems($selectedCategory, $currentPage, $itemsPerPage);
            $faqItems = $faqData['items'];
            $faqPagination = [
                'currentPage' => $faqData['currentPage'],
                'totalPages' => $faqData['totalPages'],
                'totalItems' => $faqData['totalItems'],
                'itemsPerPage' => $faqData['itemsPerPage']
            ];
            $view_content = '../app/views/pages/FAQ.php';
            $pageTitle = 'FAQ';
        } else {
            $qnaData = $qnaController->getQuestions($selectedCategory, $currentPage, $itemsPerPage);
            $qnaItems = $qnaData['items'];
            $qnaPagination = [
                'currentPage' => $qnaData['currentPage'],
                'totalPages' => $qnaData['totalPages'],
                'totalItems' => $qnaData['totalItems'],
                'itemsPerPage' => $qnaData['itemsPerPage']
            ];
            $view_content = '../app/views/pages/QnA.php';
            $pageTitle = 'Hỏi đáp';
        }
        break;
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

            $tenCauHoi = trim($_POST['ten_cau_hoi'] ?? '');
            $maLoai = (int) ($_POST['ma_loai'] ?? 0);

            // Validate question text
            $errors = [];
            if ($tenCauHoi === '') {
                $errors[] = 'Vui lòng nhập nội dung câu hỏi.';
            } elseif (strlen($tenCauHoi) < 10) {
                $errors[] = 'Câu hỏi phải có ít nhất 10 ký tự.';
            } elseif (strlen($tenCauHoi) > 255) {
                $errors[] = 'Câu hỏi không được vượt quá 255 ký tự.';
            }

            // Validate category
            if ($maLoai <= 0) {
                $errors[] = 'Vui lòng chọn chủ đề hợp lệ.';
            } else {
                // Verify category exists in DB
                $allCategories = $qnaController->getCategories();
                $categoryExists = false;
                foreach ($allCategories as $cat) {
                    if ((int) $cat['ma_loai'] === $maLoai) {
                        $categoryExists = true;
                        break;
                    }
                }
                if (!$categoryExists) {
                    $errors[] = 'Chủ đề được chọn không tồn tại.';
                }
            }

            // Validate images if uploaded
            if (!empty($_FILES['images']['name'][0])) {
                $maxFiles = 5;
                $maxSize = 3 * 1024 * 1024;
                $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
                $fileCount = count(array_filter($_FILES['images']['name']));
                
                if ($fileCount > $maxFiles) {
                    $errors[] = "Không được tải lên quá $maxFiles ảnh (hiện tại: $fileCount).";
                }

                for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
                    if (empty($_FILES['images']['name'][$i])) continue;
                    if ($_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) {
                        $errors[] = "Lỗi tải lên ảnh {$_FILES['images']['name'][$i]}: " . 
                            ($_FILES['images']['error'][$i] === UPLOAD_ERR_INI_SIZE ? 
                            'Tệp quá lớn' : 'Lỗi hệ thống');
                        continue;
                    }

                    $fileSize = $_FILES['images']['size'][$i];
                    $fileMime = mime_content_type($_FILES['images']['tmp_name'][$i]);
                    
                    if ($fileSize > $maxSize) {
                        $errors[] = "Ảnh '{$_FILES['images']['name'][$i]}' vượt quá 3MB.";
                    }
                    if (!in_array($fileMime, $allowedMimes)) {
                        $errors[] = "Ảnh '{$_FILES['images']['name'][$i]}' không phải định dạng hỗ trợ (PNG, JPEG, WebP).";
                    }
                }
            }

            if (!empty($errors)) {
                $_SESSION['qna_form_error'] = implode(' ', $errors);
                header('Location: ' . BASE_URL . '/public/index.php?page=qna_ask');
                exit;
            }

            $qnaController->createQuestion($tenCauHoi, $maLoai, (int) $_SESSION['userid']);
            $_SESSION['qna_form_success'] = 'Câu hỏi của bạn đã được ghi nhận. Chúng tôi sẽ cập nhật phần trả lời khi có phản hồi.';
            header('Location: ' . BASE_URL . '/public/index.php?page=qna');
            exit;
        }

        $qnaCategories = $qnaController->getCategories();
        $view_content = '../app/views/pages/QnAAsk.php';
        $pageTitle = 'Đặt câu hỏi';
        break;
    case 'contact':
        $view_content = '../app/views/pages/Contact.php';
        $pageTitle = 'Liên hệ';
        break;
    case 'admin':
        $view_content = '../app/views/admin/adminLayout.php';
        $pageTitle = 'Admin Dashboard';
        break;
    default:
        $view_content = '../app/views/pages/404.php';
        $pageTitle = 'Lỗi 404';
        break;
}

if ($page == 'admin') {
    require_once '../app/views/admin/adminLayout.php';
}
else {
    require_once '../app/views/template.php';
}
?>