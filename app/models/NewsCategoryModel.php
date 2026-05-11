<?php

class NewsCategoryModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    /* 
    =======================================================
    * Hàm sử dụng cho ADMIN
    ======================================================= 
    */

    // Lấy tất cả loại bài viết
    public function getAllCategories() {
        $sql = "SELECT * FROM loai_bai_viet WHERE trang_thai = 'active' ORDER BY ten_loai ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết 1 loại bài viết theo ID
    public function getCategoryByIdForAdmin($id) {
        $sql = "SELECT * FROM loai_bai_viet WHERE ma_loai = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertAndGetId($data) {
        $sql = "INSERT INTO loai_bai_viet (ten_loai, slug, trang_thai) 
                VALUES (?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $data['ten_loai'],
            $data['slug'],
            $data['trang_thai'],
        ]);

        if ($result) {
            // Hàm này của PDO sẽ trả về ma_loai vừa mới INSERT xong
            return $this->db->lastInsertId();
        }
        return false;
    }

    /* 
    =======================================================
    * Hàm sử dụng cho USER
    ======================================================= 
    */

    // Lấy danh mục dựa trên ID
    public function getCategoryById($id) {
        $sql = "SELECT * FROM loai_bai_viet WHERE ma_loai = ? AND trang_thai = 'active'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy danh mục theo slug
    public function getCategoryBySlug($slug) {
        $sql = "SELECT * FROM loai_bai_viet WHERE slug = ? AND trang_thai = 'active'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$slug]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy danh mục kèm theo danh sách bài viết mới nhất của danh mục đó
    public function getCategoriesWithTopNews($limitCategories = 4, $limitNewsPerCategory = 5) {
        // Dùng bindValue để ép kiểu dữ liệu
        // Lấy danh sách các danh mục trước
        $sqlCat = "SELECT * FROM loai_bai_viet WHERE trang_thai = 'active' ORDER BY ma_loai ASC LIMIT :limitCat";
        $stmtCat = $this->db->prepare($sqlCat);
        $stmtCat->bindValue(':limitCat', $limitCategories, PDO::PARAM_INT);
        $stmtCat->execute();
        
        $categories = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

        // Cứ mỗi danh mục -> lấy 5 bài viết
        foreach ($categories as $key => $cat) {
            $sqlNews = "SELECT bai_viet.*, TRIM(CONCAT(COALESCE(u.ho_va_ten_dem, ''), ' ', COALESCE(u.ten, ''))) AS ho_ten 
                        FROM bai_viet 
                        LEFT JOIN nguoi_dung u ON bai_viet.administrator_userid = u.userid
                        WHERE bai_viet.ma_loai = :ma_loai AND bai_viet.trang_thai = 'da_dang'
                        ORDER BY bai_viet.ngay_dang DESC 
                        LIMIT :limitNews";
                        
            $stmtNews = $this->db->prepare($sqlNews);
            $stmtNews->bindValue(':ma_loai', $cat['ma_loai'], PDO::PARAM_INT);
            $stmtNews->bindValue(':limitNews', $limitNewsPerCategory, PDO::PARAM_INT);
            $stmtNews->execute();

            // Ép mảng bài viết vào thành 1 thuộc tính của danh mục
            $categories[$key]['danh_sach_bai_viet'] = $stmtNews->fetchAll(PDO::FETCH_ASSOC);
        }

        return $categories;
    }
}
?>