<?php

require_once __DIR__ . '/../core/Database.php';

class ProductModel {
    private $db;
    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getAllCategories() {
        $sql = "SELECT * FROM loai_san_pham ORDER BY ten_loai ASC"; // all categories trong bảng loại sp sắp xếp a-z
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getProductById($id) {
        // tìm trong bàng sp cuốn sách có id = ? và thể loại của nó
        $sql = "SELECT sp.*, lsp.ten_loai 
                FROM san_pham sp
                LEFT JOIN loai_san_pham lsp ON sp.ma_loai = lsp.ma_loai
                WHERE sp.ma_san_pham = ? LIMIT 1";
                
        $stmt = $this->db->prepare($sql);
        
        // truyền $id thật
        $stmt->execute([$id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProductImages($productId) {
        // lấy product images với is_primary đầu tiên và các ảnh còn lại sắp xếp theo thứ tự
        $sql = "SELECT * FROM anh_san_pham WHERE ma_san_pham = ?
                ORDER BY is_primary DESC, so_thu_tu ASC"; // 1 xep truoc, may thang 0 con lai sap xep theo so thu tu sau
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPrimaryImage($productId) {
        $sql = "SELECT url_anh FROM anh_san_pham WHERE ma_san_pham = ? AND is_primary = 1 LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['url_anh'] : 'default_book_cover.png'; // trả về ảnh mặc định
    }

    public function countProducts($keyword = '', $categoryId = null) {
        // đếm tổng số lượng sách thỏa mãn điều kiện tìm kiếm để phân trang
        $sql = "SELECT COUNT(*) as total FROM san_pham WHERE is_active = 1";
        $params = [];
        if (!empty($keyword)) {
            $sql .= " AND (ten_san_pham LIKE ? OR mo_ta LIKE ?)";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }
        if ($categoryId) {
            $sql .= " AND ma_loai = ?";
            $params[] = $categoryId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getProducts($page = 1, $limit = 12, $keyword = '', $categoryId = null) {
        // logic phân trang
        // nếu trang 3 thì (3-1)*12 = 24 -> bỏ qua 24 sản phẩm đầu
        $offset = ($page - 1) * $limit;

        // lấy 12 ($limit) cuốn sách mới nhất, thể loại, ảnh đại diện của nó thỏa mãn điều kiện search và thuộc về trang hiện tại
        $sql = "SELECT sp.*, lsp.ten_loai,
                (SELECT url_anh FROM anh_san_pham
                WHERE ma_san_pham = sp.ma_san_pham AND is_primary = 1 LIMIT 1) as anh_chinh
                FROM san_pham sp
                LEFT JOIN loai_san_pham lsp ON sp.ma_loai = lsp.ma_loai
                WHERE sp.is_active = 1";
        $params = [];
        if (!empty($keyword)) {
            $sql .= " AND (sp.ten_san_pham LIKE ? OR sp.mo_ta LIKE ?)";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }
        if ($categoryId) {
            $sql .= " AND sp.ma_loai = ?";
            $params[] = $categoryId;
        }
        $sql .= " ORDER BY sp.ma_san_pham DESC LIMIT $limit OFFSET $offset";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ADMIN CRUD
    public function addProduct($name, $desc, $price, $catId) {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO san_pham (ten_san_pham, mo_ta, gia_san_pham, ma_loai, is_active)
                 VALUES (?, ?, ?, ?, 1)"
            );
            $stmt->execute([$name, $desc, $price, $catId]);
            // lastInsertId() = lấy ID vừa được tạo
            return ['success' => true, 'product_id' => $this->db->lastInsertId()];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateProduct($id, $name, $desc, $price, $catId, $isActive) {
        try {
            $stmt = $this->db->prepare(
                "UPDATE san_pham 
                 SET ten_san_pham=?, mo_ta=?, gia_san_pham=?, ma_loai=?, is_active=? 
                 WHERE ma_san_pham=?"
            );
            $stmt->execute([$name, $desc, $price, $catId, $isActive, $id]);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    public function deleteProduct($id) {
        $stmt = $this->db->prepare(
            "UPDATE san_pham SET is_active = 0 WHERE ma_san_pham = ?"
        );
        $stmt->execute([$id]);
        return ['success' => true];
    }

    public function getAllProductsAdmin($page = 1, $limit = 20, $keyword = '') {
        $offset = ($page - 1) * $limit;
        // admin thì không có is active = 1, fetch hết
        $sql = "SELECT sp.*, lsp.ten_loai,
                (SELECT url_anh FROM anh_san_pham WHERE ma_san_pham = sp.ma_san_pham AND is_primary = 1 LIMIT 1) as anh_chinh
                FROM san_pham sp LEFT JOIN loai_san_pham lsp ON sp.ma_loai = lsp.ma_loai";
        $params = [];
        if (!empty($keyword)) {
            $sql .= " WHERE (sp.ten_san_pham LIKE ? OR sp.mo_ta LIKE ?)";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }
        $sql .= " ORDER BY sp.ma_san_pham DESC LIMIT $limit OFFSET $offset";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
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


}
?>