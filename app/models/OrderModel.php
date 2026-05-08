<?php
require_once __DIR__ . '/../core/Database.php';

class OrderModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function createOrder($userId, $address, $paymentMethod) {
        try {
            $this->db->beginTransaction();

            // kiểm tra có cuốn sách nào trong giỏ không
            $sql = "SELECT ct.ma_san_pham, ct.so_luong, sp.gia_san_pham
                    FROM chi_tiet_gio_hang ct
                    JOIN gio_hang gh ON ct.ma_gio_hang = gh.ma_gio_hang
                    JOIN san_pham sp ON ct.ma_san_pham = sp.ma_san_pham
                    WHERE gh.member_userid = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // items không có gì thì rollback, báo lỗi
            if (empty($items)) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Giỏ hàng trống'];
            }

            // tính giá, đếm số lượng từ items đã querry
            $total = 0;
            $totalQty = 0;
            foreach ($items as $item) {
                $total += $item['gia_san_pham'] * $item['so_luong'];
                $totalQty += $item['so_luong'];
            }

            // insert vào bảng đơn hàng
            $stmt = $this->db->prepare(
                "INSERT INTO don_hang (member_userid, tong_tien, dia_chi_giao_hang, phuong_thuc_thanh_toan, so_luong_san_pham, trang_thai_don_hang, trang_thai_giao_dich)
                 VALUES (?, ?, ?, ?, ?, 'pending', 'pending')"
            );
            $stmt->execute([$userId, $total, $address, $paymentMethod, $totalQty]);
            $orderId = $this->db->lastInsertId(); // lấy mã hóa đơn vừa insert

            // insert chi_tiet_don_hang (loop từng item)
            $stmt = $this->db->prepare("INSERT INTO chi_tiet_don_hang (ma_don, ma_san_pham, so_luong, gia) VALUES (?, ?, ?, ?)");
            foreach ($items as $item) {
                $stmt->execute([$orderId, $item['ma_san_pham'], $item['so_luong'], $item['gia_san_pham']]);
            }

            // xóa giỏ hàng
            $cartStmt = $this->db->prepare("SELECT ma_gio_hang FROM gio_hang WHERE member_userid = ?");
            $cartStmt->execute([$userId]);
            $cart = $cartStmt->fetch(PDO::FETCH_ASSOC);
            if ($cart) {
                $this->db->prepare("DELETE FROM chi_tiet_gio_hang WHERE ma_gio_hang = ?")->execute([$cart['ma_gio_hang']]);
            }

            $this->db->commit();
            return ['success' => true, 'order_id' => $orderId];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getOrdersByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM don_hang WHERE member_userid = ? ORDER BY ngay_dat DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderById($orderId) {
        $stmt = $this->db->prepare("SELECT * FROM don_hang WHERE ma_don = ? LIMIT 1");
        $stmt->execute([$orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrderDetails($orderId) {
        // lấy danh sách sách trong 1 đơn hàng (kèm tên, ảnh và giá tại thời điểm mua)
        $sql = "SELECT ct.*, sp.ten_san_pham,
                (SELECT url_anh FROM anh_san_pham WHERE ma_san_pham = sp.ma_san_pham AND is_primary = 1 LIMIT 1) as anh_chinh
                FROM chi_tiet_don_hang ct
                JOIN san_pham sp ON ct.ma_san_pham = sp.ma_san_pham
                WHERE ct.ma_don = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateOrderStatus($orderId, $status) {
        $stmt = $this->db->prepare("UPDATE don_hang SET trang_thai_don_hang = ? WHERE ma_don = ?");
        $stmt->execute([$status, $orderId]);
        return ['success' => true];
    }

    public function getAllOrders($page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT dh.*, nd.username, nd.ho_va_ten_dem, nd.ten
                FROM don_hang dh
                JOIN nguoi_dung nd ON dh.member_userid = nd.userid
                ORDER BY dh.ngay_dat DESC LIMIT $limit OFFSET $offset";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countOrders() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM don_hang");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>
