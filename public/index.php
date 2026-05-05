<?php
session_start();
define('BASE_URL', 'http://localhost/MobileS');

// Load Auth Controller
require_once '../app/controllers/AuthController.php';

// Kiểm tra action
$action = isset($_GET['action']) ? $_GET['action'] : null;

if ($action === 'register') {
    $authController = new AuthController();
    $authController->handleRegister();
    exit;
} elseif ($action === 'login') {
    $authController = new AuthController();
    $authController->handleLogin();
    exit;
} elseif ($action === 'logout') {
    $authController = new AuthController();
    $authController->logout();
    exit;
}

// Nếu không có action, kiểm tra page
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

if ($page === 'login') {
    $authController = new AuthController();
    $authController->showLoginForm();
    exit;
} elseif ($page === 'register') {
    $authController = new AuthController();
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
    default:
        $view_content = '../app/views/pages/404.php';
        $pageTitle = 'Lỗi 404';
        break;
}

require_once '../app/views/template.php';
?>