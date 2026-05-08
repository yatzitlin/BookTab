<?php
require_once __DIR__ . '/../core/Database.php';

class CartModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // tạo giả hàng cho user nếu chưa có giỏ, có rồi thì thôi
    public function getOrCreateCart($userId) {
        $stmt = $this->db->prepare("SELECT ma_gio_hang FROM gio_hang WHERE member_userid = ? LIMIT 1");
        $stmt->execute([$userId]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($cart) return $cart['ma_gio_hang'];

        $stmt = $this->db->prepare("INSERT INTO gio_hang (member_userid) VALUES (?)");
        $stmt->execute([$userId]);
        return $this->db->lastInsertId();
    }

    public function getCartItems($userId) {
        // lấy danh sách cuốn sách, tên, giá, url ảnh primary và số lượng có trong giỏ hàng của người dùng có id = userId
        // join qua giỏ hàng để lấy user id để lọc WHERE
        $sql = "SELECT ct.*, sp.ten_san_pham, sp.gia_san_pham, sp.is_active,
                (SELECT url_anh FROM anh_san_pham WHERE ma_san_pham = sp.ma_san_pham AND is_primary = 1 LIMIT 1) as anh_chinh
                FROM chi_tiet_gio_hang ct
                JOIN gio_hang gh ON ct.ma_gio_hang = gh.ma_gio_hang
                JOIN san_pham sp ON ct.ma_san_pham = sp.ma_san_pham
                WHERE gh.member_userid = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addToCart($userId, $productId, $quantity = 1) {
        try {
            $cartId = $this->getOrCreateCart($userId); // lấy id giỏ của người dùng chưa thì tạo mới
            // lấy số lượng của product trong giỏ này
            $stmt = $this->db->prepare("SELECT so_luong FROM chi_tiet_gio_hang WHERE ma_gio_hang = ? AND ma_san_pham = ?");
            $stmt->execute([$cartId, $productId]);
            // giỏ không có thì existing không có data
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing) { 
                // có data thì cộng quantity
                $newQty = $existing['so_luong'] + $quantity;
                $stmt = $this->db->prepare("UPDATE chi_tiet_gio_hang SET so_luong = ? WHERE ma_gio_hang = ? AND ma_san_pham = ?");
                $stmt->execute([$newQty, $cartId, $productId]);
            } else {
                // không có data tạo dòng mới
                $stmt = $this->db->prepare("INSERT INTO chi_tiet_gio_hang (ma_gio_hang, ma_san_pham, so_luong) VALUES (?, ?, ?)");
                $stmt->execute([$cartId, $productId, $quantity]);
            }
            return ['success' => true, 'message' => 'Đã thêm vào giỏ hàng'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateCartItem($userId, $productId, $quantity) {
        $cartId = $this->getOrCreateCart($userId); // lấy cái id giỏ
        if ($quantity <= 0) { // nếu nhập = 0 thì xóa trong giỏ
            return $this->removeFromCart($userId, $productId);
        }
        // nhập khác thì cập nhật số lượng đè lên
        $stmt = $this->db->prepare("UPDATE chi_tiet_gio_hang SET so_luong = ? WHERE ma_gio_hang = ? AND ma_san_pham = ?");
        $stmt->execute([$quantity, $cartId, $productId]);
        return ['success' => true];
    }

    public function removeFromCart($userId, $productId) {
        $cartId = $this->getOrCreateCart($userId); // lấy id giỏ
        // xóa hẳn dòng chứa cuốn sách này trong giỏ của user
        $stmt = $this->db->prepare("DELETE FROM chi_tiet_gio_hang WHERE ma_gio_hang = ? AND ma_san_pham = ?");
        $stmt->execute([$cartId, $productId]);
        return ['success' => true];
    }

    public function clearCart($userId) {
        $cartId = $this->getOrCreateCart($userId);
        // xóa mọi thứ torng giỏ hàng
        $stmt = $this->db->prepare("DELETE FROM chi_tiet_gio_hang WHERE ma_gio_hang = ?");
        $stmt->execute([$cartId]);
    }

    public function getCartTotal($userId) {
        // tính tổng (giá * số lượng) của tất cả hàng đã querry bằng join và where
        $sql = "SELECT SUM(sp.gia_san_pham * ct.so_luong) as total
                FROM chi_tiet_gio_hang ct
                JOIN gio_hang gh ON ct.ma_gio_hang = gh.ma_gio_hang
                JOIN san_pham sp ON ct.ma_san_pham = sp.ma_san_pham
                WHERE gh.member_userid = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0; // null thì = 0
    }

    public function getCartItemCount($userId) {
        // cộng dồn tất cả số lượng của các món đồ để ra tổng số cuốn sách đang có trong giỏ
        $sql = "SELECT SUM(ct.so_luong) as cnt
                FROM chi_tiet_gio_hang ct
                JOIN gio_hang gh ON ct.ma_gio_hang = gh.ma_gio_hang
                WHERE gh.member_userid = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['cnt'] ?? 0; // giỏ trống thì trả về 0
    }
}
?>
