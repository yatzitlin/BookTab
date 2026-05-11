<?php

class CommentModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // ============================================================
    // User - Lấy bình luận gốc (không có parent)
    // ============================================================
    public function getTopLevelComments($ma_bai_viet, $limit = 10, $offset = 0) {
        $sql = "SELECT bl.ma_binh_luan, bl.noi_dung, bl.ngay_tao, bl.luot_thich, bl.url_anh,
                       nd.userid, nd.ho_va_ten_dem, nd.ten,
                       COALESCE(replies.so_phan_hoi, 0) AS so_phan_hoi
                FROM binh_luan bl
                LEFT JOIN nguoi_dung nd ON bl.userid = nd.userid
                LEFT JOIN (
                    SELECT binh_luan_cha, COUNT(*) AS so_phan_hoi
                    FROM binh_luan
                    WHERE binh_luan_cha IS NOT NULL
                    GROUP BY binh_luan_cha
                ) replies ON replies.binh_luan_cha = bl.ma_binh_luan
                WHERE bl.ma_bai_viet = ? AND (bl.binh_luan_cha IS NULL OR bl.binh_luan_cha = 0) AND bl.trang_thai = 'hien'
                ORDER BY bl.ngay_tao DESC
                LIMIT ? OFFSET ?";
        
        $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, (int) $ma_bai_viet, PDO::PARAM_INT);
            $stmt->bindValue(2, (int) $limit, PDO::PARAM_INT);
            $stmt->bindValue(3, (int) $offset, PDO::PARAM_INT);
            $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Đếm bình luận gốc
    public function countTopLevelComments($ma_bai_viet) {
        $sql = "SELECT COUNT(*) as total FROM binh_luan 
                WHERE ma_bai_viet = ? AND (binh_luan_cha IS NULL OR binh_luan_cha = 0) AND trang_thai = 'hien'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, (int) $ma_bai_viet, PDO::PARAM_INT);
        $stmt->execute();
        return (int) (($stmt->fetch(PDO::FETCH_ASSOC))['total'] ?? 0);
    }

    // ============================================================
    // User - Lấy reply của một bình luận gốc
    // ============================================================
    public function getRepliesByParentId($parent_id) {
        $sql = "SELECT bl.ma_binh_luan, bl.noi_dung, bl.ngay_tao, bl.luot_thich, bl.url_anh,
                       nd.userid, nd.ho_va_ten_dem, nd.ten
                FROM binh_luan bl
                LEFT JOIN nguoi_dung nd ON bl.userid = nd.userid
                WHERE bl.binh_luan_cha = ? AND bl.trang_thai = 'hien'
                ORDER BY bl.ngay_tao ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, (int) $parent_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ============================================================
    // User - Thêm bình luận mới
    // ============================================================
    public function insertComment($ma_bai_viet, $userid, $noi_dung, $binh_luan_cha = null) {
        $sql = "INSERT INTO binh_luan (ma_bai_viet, userid, noi_dung, binh_luan_cha, trang_thai)
                VALUES (?, ?, ?, ?, 'hien')";
        
        $stmt = $this->conn->prepare($sql);
        $success = $stmt->execute([$ma_bai_viet, $userid, $noi_dung, $binh_luan_cha]);
        
        if ($success) {
            return (int) $this->conn->lastInsertId();
        }
        return false;
    }

    // ============================================================
    // Admin - Lấy tất cả bình luận
    // ============================================================
    public function getAllCommentsForAdmin($cat_id = 0) {
        $sql = "SELECT bl.ma_binh_luan, bl.noi_dung, bl.ngay_tao, bl.trang_thai,
                       nd.ho_va_ten_dem, nd.ten,
                       bv.tieu_de, bv.slug,
                       bl.binh_luan_cha,
                       lbv.ma_loai
                FROM binh_luan bl
                LEFT JOIN nguoi_dung nd ON bl.userid = nd.userid
                LEFT JOIN bai_viet bv ON bl.ma_bai_viet = bv.ma_bai_viet
                LEFT JOIN loai_bai_viet lbv ON bv.ma_loai = lbv.ma_loai
                WHERE 1=1";
        
        $params = [];
        if ($cat_id > 0) {
            $sql .= " AND lbv.ma_loai = ?";
            $params[] = $cat_id;
        }
        
        $sql .= " ORDER BY bl.ngay_tao DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ============================================================
    // Admin - Đổi trạng thái hiển thị
    // ============================================================
    public function toggleVisibility($id) {
        $sql = "SELECT trang_thai FROM binh_luan WHERE ma_binh_luan = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        $current = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$current) return false;
        
        $newStatus = ($current['trang_thai'] === 'hien') ? 'an' : 'hien';
        
        $sql = "UPDATE binh_luan SET trang_thai = ? WHERE ma_binh_luan = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$newStatus, $id]);
    }

    // ============================================================
    // Admin - Xóa bình luận
    // ============================================================
    public function deleteComment($id) {
        // Xóa tất cả reply trước
        $sql = "DELETE FROM binh_luan WHERE binh_luan_cha = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        
        // Xóa bình luận gốc
        $sql = "DELETE FROM binh_luan WHERE ma_binh_luan = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    // ============================================================
    // Admin - Lấy chi tiết 1 bình luận
    // ============================================================
    public function getCommentById($id) {
        $sql = "SELECT bl.*, nd.ho_va_ten_dem, nd.ten, bv.tieu_de
                FROM binh_luan bl
                LEFT JOIN nguoi_dung nd ON bl.userid = nd.userid
                LEFT JOIN bai_viet bv ON bl.ma_bai_viet = bv.ma_bai_viet
                WHERE bl.ma_binh_luan = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
