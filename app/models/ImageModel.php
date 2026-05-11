<?php

class ImageModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả ảnh của 1 sản phẩm
    public function getByProductId($productId) {
        $sql = "
            SELECT *
            FROM anh_san_pham
            WHERE ma_san_pham = ?
            ORDER BY is_primary DESC, so_thu_tu ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$productId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy ảnh chính
    public function getPrimaryImage($productId) {
        $sql = "
            SELECT *
            FROM anh_san_pham
            WHERE ma_san_pham = ? AND is_primary = 1
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$productId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm ảnh
    public function insert($productId, $url, $alt = null, $isPrimary = 0) {
        $sql = "
            INSERT INTO anh_san_pham (ma_san_pham, url_anh, alt_text, is_primary)
            VALUES (?, ?, ?, ?)
        ";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$productId, $url, $alt, $isPrimary]);
    }

    // Xóa ảnh
    public function delete($id) {
        $sql = "DELETE FROM anh_san_pham WHERE ma_anh = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}