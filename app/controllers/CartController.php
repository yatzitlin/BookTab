<?php
require_once __DIR__ . '/BaseController.php';

class CartController extends BaseController {
    private $cartModel;

    public function __construct($dbConnection) {
        parent::__construct($dbConnection);
        $this->cartModel = $this->loadModel('CartModel');
    }

    public function showCart() {
        // bắt buộc phải đăng nhập
        if (!isset($_SESSION['userid'])) {
            header('Location: ' . BASE_URL . '/public/index.php?page=login');
            exit;
        }
        $cartItems = $this->cartModel->getCartItems($_SESSION['userid']);
        $cartTotal = $this->cartModel->getCartTotal($_SESSION['userid']);

        $view_content = dirname(__FILE__) . '/../views/pages/Cart.php';
        $pageTitle    = 'Giỏ hàng';
        require_once dirname(__FILE__) . '/../views/template.php';
    }

    public function handleAddToCart() {
        if (!isset($_SESSION['userid'])) {
            header('Location: ' . BASE_URL . '/public/index.php?page=login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
            header('Location: ' . BASE_URL . '/public/index.php?page=products'); exit; 
        }

        $productId = intval($_POST['product_id'] ?? 0);
        $quantity  = max(1, intval($_POST['quantity'] ?? 1));

        if ($productId > 0) {
            // check sản phẩm active kh trc khi add
            $check = $this->db->prepare("SELECT is_active FROM san_pham WHERE ma_san_pham = ? LIMIT 1");
            $check->execute([$productId]);
            $sp = $check->fetch(PDO::FETCH_ASSOC);
            if ($sp && $sp['is_active'] == 1) {
                $this->cartModel->addToCart($_SESSION['userid'], $productId, $quantity);
            }
        }
        header('Location: ' . BASE_URL . '/public/index.php?page=product_detail&id=' . $productId . '&added=1');
        exit;
    }

    public function handleUpdateCart() {
        if (!isset($_SESSION['userid']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/public/index.php?page=cart');
            exit;
        }
        $productId = intval($_POST['product_id'] ?? 0);
        $quantity  = intval($_POST['quantity'] ?? 1);
        if ($productId > 0) {
            // quantity <= 0 thì CartModel sẽ tự gọi removeFromCart
            $this->cartModel->updateCartItem($_SESSION['userid'], $productId, $quantity);
        }
        header('Location: ' . BASE_URL . '/public/index.php?page=cart');
        exit;
    }

    public function handleRemoveFromCart() {
        if (!isset($_SESSION['userid']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/public/index.php?page=cart');
            exit;
        }
        $productId = intval($_POST['product_id'] ?? 0);
        if ($productId > 0) {
            $this->cartModel->removeFromCart($_SESSION['userid'], $productId);
        }
        header('Location: ' . BASE_URL . '/public/index.php?page=cart');
        exit;
    }

    public function showCheckout() {
        if (!isset($_SESSION['userid'])) {
            header('Location: ' . BASE_URL . '/public/index.php?page=login');
            exit;
        }
        $cartItems = $this->cartModel->getCartItems($_SESSION['userid']);
        $cartTotal = $this->cartModel->getCartTotal($_SESSION['userid']);

        // giỏ trống → không cho vào trang đặt hàng
        if (empty($cartItems)) {
            header('Location: ' . BASE_URL . '/public/index.php?page=cart');
            exit;
        }

        $view_content = dirname(__FILE__) . '/../views/pages/Checkout.php';
        $pageTitle    = 'Đặt hàng';
        require_once dirname(__FILE__) . '/../views/template.php';
    }
}
?>
