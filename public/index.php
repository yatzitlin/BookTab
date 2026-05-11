<?php
define('BASE_URL', 'http://localhost/BookTab');

require_once '../app/controllers/AuthController.php';
require_once '../app/core/Database.php';
require_once '../app/controllers/ProductController.php';
require_once '../app/controllers/CartController.php';
require_once '../app/controllers/OrderController.php';


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
} elseif ($action === 'add_review') {
    $productCtrl = new ProductController($dbConnection);
    $productCtrl->handleAddReview(); exit;
} elseif ($action === 'add_to_cart') {
    $cartCtrl = new CartController($dbConnection);
    $cartCtrl->handleAddToCart(); exit;
} elseif ($action === 'update_cart') {
    $cartCtrl = new CartController($dbConnection);
    $cartCtrl->handleUpdateCart(); exit;
} elseif ($action === 'remove_cart') {
    $cartCtrl = new CartController($dbConnection);
    $cartCtrl->handleRemoveFromCart(); exit;
} elseif ($action === 'checkout') {
    $orderCtrl = new OrderController($dbConnection);
    $orderCtrl->handleCheckout(); exit;
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

// PRODUCT MODULE
if ($page === 'products') {
    $productCtrl = new ProductController($dbConnection);
    $productCtrl->showProducts(); exit;
} elseif ($page === 'product_detail') {
    $productCtrl = new ProductController($dbConnection);
    $productCtrl->showProductDetail(); exit;
}

// CART MODULE
if ($page === 'cart') {
    $cartCtrl = new CartController($dbConnection);
    $cartCtrl->showCart(); exit;
} elseif ($page === 'checkout') {
    $cartCtrl = new CartController($dbConnection);
    $cartCtrl->showCheckout(); exit;
} elseif ($action === 'admin_product') {
    $productCtrl = new ProductController($dbConnection);
    $productCtrl->handleAdminProductAction(); exit;
} elseif ($action === 'admin_order') {
    $orderCtrl = new OrderController($dbConnection);
    $orderCtrl->handleUpdateOrderStatus(); exit;
}


// ORDER MODULE
if ($page === 'orders') {
    $orderCtrl = new OrderController($dbConnection);
    $orderCtrl->showOrders(); exit;
} elseif ($page === 'order_detail') {
    $orderCtrl = new OrderController($dbConnection);
    $orderCtrl->showOrderDetail(); exit;
}




// Routing cho các page thường
switch ($page) {
    case 'home':
        $view_content = '../app/views/pages/Home.php';
        $pageTitle = 'Trang chủ';
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