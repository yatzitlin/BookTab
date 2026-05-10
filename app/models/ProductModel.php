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
}