<?php
require_once __DIR__ . '/BaseController.php';

class OrderController extends BaseController {
    private $orderModel;

    public function __construct($dbConnection) {
        parent::__construct($dbConnection);
        $this->orderModel = $this->loadModel('OrderModel');
    }

    public function handleCheckout() {
        // bắt buộc đăng nhập và phải là POST
        if (!isset($_SESSION['userid']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/public/index.php?page=cart');
            exit;
        }
        // CSRF bắt buộc ở đây vì tạo ra đơn hàng thật
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            header('Location: ' . BASE_URL . '/public/index.php?page=cart');
            exit;
        }

        $address       = trim($_POST['dia_chi'] ?? '');
        $paymentMethod = trim($_POST['phuong_thuc'] ?? 'COD');

        // địa chỉ rỗng → không cho đặt hàng
        if (empty($address)) {
            header('Location: ' . BASE_URL . '/public/index.php?page=checkout&error=address');
            exit;
        }

        $result = $this->orderModel->createOrder($_SESSION['userid'], $address, $paymentMethod);
        if ($result['success']) {
            header('Location: ' . BASE_URL . '/public/index.php?page=orders&success=1');
        } else {
            header('Location: ' . BASE_URL . '/public/index.php?page=checkout&error=failed');
        }
        exit;
    }

    public function showOrders() {
        if (!isset($_SESSION['userid'])) {
            header('Location: ' . BASE_URL . '/public/index.php?page=login');
            exit;
        }
        $orders = $this->orderModel->getOrdersByUserId($_SESSION['userid']);

        $view_content = dirname(__FILE__) . '/../views/pages/OrderHistory.php';
        $pageTitle    = 'Đơn hàng của tôi';
        require_once dirname(__FILE__) . '/../views/template.php';
    }

    public function showOrderDetail() {
        if (!isset($_SESSION['userid'])) {
            header('Location: ' . BASE_URL . '/public/index.php?page=login');
            exit;
        }
        $orderId = intval($_GET['id'] ?? 0);
        $order   = $this->orderModel->getOrderById($orderId);

        // chặn: không tồn tại HOẶC đơn hàng của người khác
        if (!$order || $order['member_userid'] != $_SESSION['userid']) {
            header('Location: ' . BASE_URL . '/public/index.php?page=orders');
            exit;
        }
        $orderDetails = $this->orderModel->getOrderDetails($orderId);

        $view_content = dirname(__FILE__) . '/../views/pages/OrderDetail.php';
        $pageTitle    = 'Chi tiết đơn hàng #' . $orderId;
        require_once dirname(__FILE__) . '/../views/template.php';
    }

    // ADMIN

    public function showAdminOrders() {
        $page        = max(1, intval($_GET['p'] ?? 1));
        $orders      = $this->orderModel->getAllOrders($page, 20);
        $totalOrders = $this->orderModel->countOrders();
        $totalPages  = ceil($totalOrders / 20);

        include dirname(__FILE__) . '/../views/admin/adminPages/AdminOrders.php';
    }

    public function handleUpdateOrderStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) return;

        $orderId = intval($_POST['ma_don'] ?? 0);
        $status  = trim($_POST['trang_thai'] ?? '');

        // Whitelist 5 trạng thái hợp lệ:
        // - 'pending'   → đơn vừa đặt, chờ admin xác nhận
        // - 'confirmed' → admin xác nhận, chuẩn bị bàn giao vận chuyển
        // - 'shipping'  → [TODO] cập nhật tự động qua webhook GHN/GHTK khi có tích hợp API
        // - 'delivered' → [TODO] cập nhật tự động qua webhook khi vận chuyển xác nhận giao thành công
        // - 'cancelled' → admin hoặc hệ thống hủy đơn
        // Hiện tại admin có thể cập nhật thủ công tất cả 5 trạng thái.
        $allowed = ['pending', 'confirmed', 'shipping', 'delivered', 'cancelled'];
        if ($orderId && in_array($status, $allowed)) {
            $this->orderModel->updateOrderStatus($orderId, $status);
        }

        header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=orders');
        exit;
    }
}
?>
