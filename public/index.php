<?php
define('BASE_URL', 'http://localhost/BookTab');

require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/AdminController.php';
require_once '../app/controllers/CompanyContact.php';
require_once '../app/core/Database.php'; 

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

$contactModel = new CompanyContactModel($dbConnection);
$contact = $contactModel->getContactInfo();

// Xử lý admin actions TRƯỚC khi include adminLayout
if ($page === 'admin' && isset($_GET['action'])) {
    require_once '../app/controllers/AdminController.php';
    $adminController = new AdminController($dbConnection);
    $action = $_GET['action'];
    
    if (method_exists($adminController, $action)) {
        $adminController->$action();
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
        $productCtrl = new ProductController($dbConnection);
        $featuredProducts = $productCtrl->showHome();
        $view_content = '../app/views/pages/Home.php';
        $pageTitle = 'Trang chủ';
<<<<<<< Updated upstream
=======

    break;
    case 'about':
        $aboutController = new AboutController($dbConnection);
        $aboutPage = $aboutController->getAboutPage();
        $view_content = '../app/views/pages/About.php';
        $pageTitle = 'Giới thiệu';
>>>>>>> Stashed changes
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
        $view_content = '../app/views/pages/QnA.php';
        $pageTitle = 'Hỏi đáp';
        break;
    case 'contact':
        $view_content = '../app/views/pages/contact.php';
        $pageTitle = 'Liên hệ';
        break;
    // Xử lý submit form
    case 'contact-send':
        require_once '../app/controllers/ContactController.php';
        $controller = new ContactController($dbConnection);
        $controller->send();
        break;
    case 'admin':
        $view_content = '../app/views/admin/adminLayout.php';
        $pageTitle = 'Admin Dashboard';
        break;
    case 'profile':
        require_once '../app/controllers/UserController.php';
        $controller = new UserController($dbConnection);
        $user = $controller->information();
        $view_content = '../app/views/user/information.php';
        break;
    case 'change-password':
        require_once '../app/controllers/UserController.php';
        $controller = new UserController($dbConnection);
        $controller->changePassword();
        $view_content = '../app/views/user/change_password.php';
        break;
    case 'update-profile':
        require_once '../app/controllers/UserController.php';
        $controller = new UserController($dbConnection);
        $controller->updateProfile();
        break;
    case 'update-avatar':
        require_once '../app/controllers/UserController.php';
        $controller = new UserController($dbConnection);
        $controller->updateAvatar();
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