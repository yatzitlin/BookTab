<?php

class NewsModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    /* 
    =======================================================
    * Hàm sử dụng cho ADMIN
    ======================================================= 
    */

    // Lấy danh sách bài viết
    public function getAllNews() {
        $sql = "SELECT b.*, l.ten_loai, n.ho_va_ten_dem, n.ten 
                FROM bai_viet b
                    LEFT JOIN loai_bai_viet l ON b.ma_loai = l.ma_loai
                JOIN nguoi_dung n ON b.administrator_userid = n.userid
                ORDER BY b.ngay_dang DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tìm kiếm bài viết theo từ khóa
    public function searchNews($keyword) {
        $sql = "SELECT b.*, l.ten_loai 
                FROM bai_viet b
                LEFT JOIN loai_bai_viet l ON b.ma_loai = l.ma_loai
                WHERE b.tieu_de LIKE ? OR b.noi_dung LIKE ?
                ORDER BY b.ngay_dang DESC";
        $stmt = $this->db->prepare($sql);
        $key = "%$keyword%";
        $stmt->execute([$key, $key]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm bài viết mới
    public function insert($data) {
        $sql = "INSERT INTO bai_viet (tieu_de, tom_tat, noi_dung, thumbnail_url, trang_thai, slug, ma_loai, administrator_userid) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['tieu_de'],
            $data['tom_tat'],
            $data['noi_dung'],
            $data['thumbnail_url'],
            $data['trang_thai'],
            $data['slug'],
            $data['ma_loai'],
            $data['administrator_userid']
        ]);
    }

    // Lấy chi tiết bài viết
    public function getById($id) {
        $sql = "SELECT * FROM bai_viet WHERE ma_bai_viet = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật bài viết
    public function update($id, $data) {
        $sql = "UPDATE bai_viet SET tieu_de = ?, tom_tat = ?, noi_dung = ?, thumbnail_url = ?, trang_thai = ?, slug = ?, ma_loai = ? 
                WHERE ma_bai_viet = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['tieu_de'], $data['tom_tat'], $data['noi_dung'], $data['thumbnail_url'], 
            $data['trang_thai'], $data['slug'], $data['ma_loai'], $id
        ]);
    }

    // Xóa bài viết
    public function delete($id) {
        $sql = "DELETE FROM bai_viet WHERE ma_bai_viet = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Kiểm tra slug đã tồn tại hay chưa
    public function isSlugExists($slug, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM bai_viet WHERE slug = ?";
        $params = [$slug];

        // Nếu là đang Update -> loại trừ ID của chính bài viết đó ra
        if ($excludeId) {
            $sql .= " AND ma_bai_viet != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    /* 
    =======================================================
    * Hàm sử dụng cho USER
    ======================================================= 
    */

    // Lấy chi tiết bài viết (Chỉ lấy bài viết có trạng thái đã đăng - "da_dang")
    public function getPublishedNewsById($id) {
        $sql = "SELECT bai_viet.*, loai_bai_viet.ten_loai, 
                   TRIM(CONCAT(COALESCE(u.ho_va_ten_dem, ''), ' ', COALESCE(u.ten, ''))) AS ho_ten
                FROM bai_viet 
                LEFT JOIN loai_bai_viet ON bai_viet.ma_loai = loai_bai_viet.ma_loai
            LEFT JOIN nguoi_dung u ON bai_viet.administrator_userid = u.userid
                WHERE bai_viet.ma_bai_viet = ? AND bai_viet.trang_thai = 'da_dang'";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);        
    }

    // Lấy toàn bộ bài viết đã đăng thuộc về một danh mục cụ thể
    public function getPublishedNewsByCategoryId($id) {
        $sql = "SELECT bai_viet.*, TRIM(CONCAT(COALESCE(u.ho_va_ten_dem, ''), ' ', COALESCE(u.ten, ''))) AS ho_ten 
                FROM bai_viet 
            LEFT JOIN nguoi_dung u ON bai_viet.administrator_userid = u.userid
                WHERE bai_viet.ma_loai = ? AND bai_viet.trang_thai = 'da_dang'
                ORDER BY bai_viet.ngay_dang DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy toàn bộ bài viết đã đăng theo slug của danh mục
    public function getPublishedNewsByCategorySlug($slug, $limit = 6, $offset = 0) {
        $sql = "SELECT bai_viet.*, loai_bai_viet.ten_loai, 
                   TRIM(CONCAT(COALESCE(u.ho_va_ten_dem, ''), ' ', COALESCE(u.ten, ''))) AS ho_ten
                FROM bai_viet
                LEFT JOIN loai_bai_viet ON bai_viet.ma_loai = loai_bai_viet.ma_loai
                LEFT JOIN nguoi_dung u ON bai_viet.administrator_userid = u.userid
                WHERE bai_viet.trang_thai = 'da_dang' AND loai_bai_viet.slug = :slug
                ORDER BY bai_viet.ngay_dang DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Đếm bài viết đã đăng theo slug danh mục
    public function countPublishedNewsByCategorySlug($slug) {
        $sql = "SELECT COUNT(*) AS total
                FROM bai_viet
                LEFT JOIN loai_bai_viet ON bai_viet.ma_loai = loai_bai_viet.ma_loai
                WHERE bai_viet.trang_thai = 'da_dang' AND loai_bai_viet.slug = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$slug]);

        return (int) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
    }

    // Lấy toàn bộ bài viết đã đăng theo từ khóa
    public function searchPublishedNews($keyword, $limit = 6, $offset = 0) {
                $sql = "SELECT bai_viet.*, loai_bai_viet.ten_loai, 
                                     TRIM(CONCAT(COALESCE(u.ho_va_ten_dem, ''), ' ', COALESCE(u.ten, ''))) AS ho_ten
                                FROM bai_viet
                                LEFT JOIN loai_bai_viet ON bai_viet.ma_loai = loai_bai_viet.ma_loai
                                LEFT JOIN nguoi_dung u ON bai_viet.administrator_userid = u.userid
                                WHERE bai_viet.trang_thai = 'da_dang'
                                    AND (bai_viet.tieu_de LIKE :keyword OR bai_viet.tom_tat LIKE :keyword OR bai_viet.noi_dung LIKE :keyword)
                                ORDER BY bai_viet.ngay_dang DESC
                                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $like = '%' . $keyword . '%';
        $stmt->bindValue(':keyword', $like, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Đếm bài viết đã đăng theo từ khóa
    public function countPublishedNewsByKeyword($keyword) {
        $sql = "SELECT COUNT(*) AS total
                FROM bai_viet
                WHERE trang_thai = 'da_dang'
                  AND (tieu_de LIKE ? OR tom_tat LIKE ? OR noi_dung LIKE ?)";

        $stmt = $this->db->prepare($sql);
        $like = '%' . $keyword . '%';
        $stmt->execute([$like, $like, $like]);

        return (int) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
    }

    // Lấy gợi ý tìm kiếm nhanh
    public function searchPublishedNewsSuggestions($keyword, $limit = 6) {
        $sql = "SELECT bai_viet.ma_bai_viet, bai_viet.tieu_de, bai_viet.thumbnail_url, bai_viet.ngay_dang, bai_viet.slug,
                   loai_bai_viet.ten_loai,
                   TRIM(CONCAT(COALESCE(u.ho_va_ten_dem, ''), ' ', COALESCE(u.ten, ''))) AS ho_ten
                FROM bai_viet
                LEFT JOIN loai_bai_viet ON bai_viet.ma_loai = loai_bai_viet.ma_loai
                LEFT JOIN nguoi_dung u ON bai_viet.administrator_userid = u.userid
                WHERE bai_viet.trang_thai = 'da_dang'
                  AND (bai_viet.tieu_de LIKE :keyword OR bai_viet.tom_tat LIKE :keyword OR bai_viet.noi_dung LIKE :keyword)
                ORDER BY bai_viet.ngay_dang DESC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $like = '%' . $keyword . '%';
        $stmt->bindValue(':keyword', $like, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách tin mới nhất
    public function getLatestPublishedNews($limit = 5) {
        // Lưu ý: trang_thai của cậu lúc nãy đặt là 'da_dang'
        $sql = "SELECT bai_viet.*, loai_bai_viet.ten_loai, 
                   TRIM(CONCAT(COALESCE(u.ho_va_ten_dem, ''), ' ', COALESCE(u.ten, ''))) AS ho_ten 
                FROM bai_viet 
                LEFT JOIN loai_bai_viet ON bai_viet.ma_loai = loai_bai_viet.ma_loai
            LEFT JOIN nguoi_dung u ON bai_viet.administrator_userid = u.userid
                WHERE bai_viet.trang_thai = 'da_dang' 
                ORDER BY bai_viet.ngay_dang DESC 
                LIMIT :limit";
                
        $stmt = $this->db->prepare($sql);
        
        // Dùng bindValue và ép kiểu PARAM_INT cho biến LIMIT
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách tin đọc nhiều nhất (Dựa vào luot_xem)
    public function getTrendingNews($limit = 5) {
        $sql = "SELECT bai_viet.*, loai_bai_viet.ten_loai, 
                   TRIM(CONCAT(COALESCE(u.ho_va_ten_dem, ''), ' ', COALESCE(u.ten, ''))) AS ho_ten 
                FROM bai_viet 
                LEFT JOIN loai_bai_viet ON bai_viet.ma_loai = loai_bai_viet.ma_loai
            LEFT JOIN nguoi_dung u ON bai_viet.administrator_userid = u.userid
                WHERE bai_viet.trang_thai = 'da_dang' 
                ORDER BY bai_viet.luot_xem DESC 
                LIMIT :limit";
                
        $stmt = $this->db->prepare($sql);

        // Dùng bindValue và ép kiểu PARAM_INT cho biến LIMIT
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy bài viết đã đăng theo slug
    public function getPublishedNewsBySlug($slug) {
        $sql = "SELECT bai_viet.*, loai_bai_viet.ten_loai, loai_bai_viet.slug AS slug_loai,
                   TRIM(CONCAT(COALESCE(u.ho_va_ten_dem, ''), ' ', COALESCE(u.ten, ''))) AS ho_ten
                FROM bai_viet 
                LEFT JOIN loai_bai_viet ON bai_viet.ma_loai = loai_bai_viet.ma_loai
                LEFT JOIN nguoi_dung u ON bai_viet.administrator_userid = u.userid
                WHERE bai_viet.slug = ? AND bai_viet.trang_thai = 'da_dang'";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$slug]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);        
    }

    // Tăng lượt xem cho bài viết mỗi khi có người nhấn vào xem
    public function increaseViewCount($id) {
        $sql = "UPDATE bai_viet SET luot_xem = luot_xem + 1 WHERE ma_bai_viet = ?";
        
        $stmt = $this->db->prepare($sql);        
        return $stmt->execute([$id]);
    }
}