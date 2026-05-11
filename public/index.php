<?php
define('BASE_URL', 'http://localhost/BookTab');

require_once '../app/controllers/AuthController.php';
require_once '../app/core/Database.php'; 
require_once '../app/controllers/NewsController.php';

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

// Routing cho các page thường
switch ($page) {
    case 'home':
        $view_content = '../app/views/pages/Home.php';
        $pageTitle = 'Trang chủ';
        break;
    case 'products':
        $view_content = '../app/views/pages/Products.php';
        $pageTitle = 'Sản phẩm';
        break;
    case 'news':
        $newsController = new NewsController($dbConnection);
        $newsAction = isset($_GET['news_action']) ? $_GET['news_action'] : 'index';

        // Xử lý gợi ý tìm kiếm
        if ($newsAction === 'search_ajax') {
            $newsController->searchAjax();
            exit;
        }

        // Xử lý trang Chi tiết bài viết
        if ($newsAction === 'detail' && isset($_GET['slug'])) {
            $newsController->detail(trim((string) $_GET['slug']));
            exit;
        }

        // Xử lý trang Danh sách bài viết
        if ($newsAction === 'list') {
            $newsController->listing();
            exit;
        }

        $newsController->index();
        exit;
        break;
    case 'qna':
        $view_content = '../app/views/pages/QnA.php';
        $pageTitle = 'Hỏi đáp';
        break;
    case 'contact':
        $view_content = '../app/views/pages/contact.php';
        $pageTitle = 'Liên hệ';
        break;
    case 'admin':
        $pageTitle = 'Admin Dashboard';
        break;
    default:
        $view_content = '../app/views/pages/404.php';
        $pageTitle = 'Lỗi 404';
        break;
}

// Admin gọi Controller
if ($page == 'admin') {
    $admin_action = isset($_GET['admin_action']) ? $_GET['admin_action'] : 'dashboard';

    require_once '../app/controllers/AdminNewsController.php';
    $newsController = new AdminNewsController($dbConnection);

    if ($admin_action === 'news') {
        $newsController->index();
        exit; // Lệnh exit báo PHP dừng lại vì trong index() đã gọi adminLayout
    } 
    elseif ($admin_action === 'news_store') {
        $newsController->store();
        exit; 
    } 
    elseif ($admin_action === 'news_edit') {
        $newsController->edit();
        exit; 
    } 
    elseif ($admin_action === 'news_update') {
        $newsController->update();
        exit;
    }
    elseif ($admin_action === 'news_delete') {
        $newsController->delete();
        exit;
    }
    elseif ($admin_action === 'news_upload_image') {
        $newsController->uploadImage();
        exit;
    }

    require_once '../app/views/admin/adminLayout.php';
} 
else {
    require_once '../app/views/template.php';
}
?>