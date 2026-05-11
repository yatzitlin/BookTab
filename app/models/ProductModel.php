<?php

class CategoryModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả danh mục
    public function getAll() {
        $sql = "SELECT * FROM loai_san_pham ORDER BY ten_loai ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh mục cha (root)
    public function getParentCategories() {
        $sql = "SELECT * FROM loai_san_pham WHERE loai_cha IS NULL";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh mục con theo cha
    public function getChildren($parentId) {
        $sql = "SELECT * FROM loai_san_pham WHERE loai_cha = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$parentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // (OPTION) đếm số sản phẩm trong từng loại
    public function getWithProductCount() {
        $sql = "
            SELECT l.*, COUNT(sp.ma_san_pham) AS total
            FROM loai_san_pham l
            LEFT JOIN san_pham sp ON l.ma_loai = sp.ma_loai
            GROUP BY l.ma_loai
            ORDER BY l.ten_loai ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
<<<<<<< Updated upstream
}
=======
    
    public function countProductsAdmin($keyword = '') {
        $sql = "SELECT COUNT(*) as total FROM san_pham";
        $params = [];
        if (!empty($keyword)) {
            $sql .= " WHERE (ten_san_pham LIKE ? OR mo_ta LIKE ?)";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function addProductImage($productId, $url, $alt = '', $isPrimary = false, $order = 0) {
        $stmt = $this->db->prepare("INSERT INTO anh_san_pham (ma_san_pham, url_anh, alt_text, is_primary, so_thu_tu) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$productId, $url, $alt, $isPrimary ? 1 : 0, $order]);
    }

    public function deleteProductImage($imageId) {
        $stmt = $this->db->prepare("DELETE FROM anh_san_pham WHERE ma_anh = ?");
        $stmt->execute([$imageId]);
    }
    public function addCategory($name, $parentId = null) {
        $stmt = $this->db->prepare("INSERT INTO loai_san_pham (ten_loai, loai_cha) VALUES (?, ?)");
        $stmt->execute([$name, $parentId]);
        return $this->db->lastInsertId();
    }

    public function getProductReviews($productId) {
        $sql = "SELECT dg.*, nd.username, nd.ho_va_ten_dem, nd.ten 
                FROM danh_gia dg
                JOIN nguoi_dung nd ON dg.member_userid = nd.userid
                WHERE dg.ma_san_pham = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAverageRating($productId) {
        $sql = "SELECT AVG(diem) as avg_rating, COUNT(*) as total_reviews FROM danh_gia WHERE ma_san_pham = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addReview($userId, $productId, $score, $content) {
        try {
            // kiểm tra xem user này đã đánh giá sản phẩm này chưa
            $check = $this->db->prepare("SELECT COUNT(*) as cnt FROM danh_gia WHERE member_userid = ? AND ma_san_pham = ?");
            $check->execute([$userId, $productId]);
            if ($check->fetch(PDO::FETCH_ASSOC)['cnt'] > 0) {
                return ['success' => false, 'message' => 'Bạn đã đánh giá sản phẩm này rồi'];
            }
            
            $stmt = $this->db->prepare("INSERT INTO danh_gia (member_userid, ma_san_pham, diem, noi_dung) VALUES (?, ?, ?, ?)");
            $stmt->execute([$userId, $productId, $score, $content]);
            return ['success' => true, 'message' => 'Đánh giá thành công'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()];
        }
    }

    public function getFeaturedProducts($limit = 4) {

        $sql = "SELECT sp.*,
                (SELECT url_anh 
                FROM anh_san_pham 
                WHERE ma_san_pham = sp.ma_san_pham 
                AND is_primary = 1 
                LIMIT 1) as anh_chinh
                
                FROM san_pham sp
                
                WHERE sp.is_active = 1
                
                ORDER BY sp.ma_san_pham DESC
                
                LIMIT ?";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>
>>>>>>> Stashed changes
