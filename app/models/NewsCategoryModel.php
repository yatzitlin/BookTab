<?php

require_once __DIR__ . '/../core/Database.php';

class NewsCategoryModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // Lấy tất cả loại bài viết
    public function getAllCategories() {
        $sql = "SELECT * FROM loai_bai_viet ORDER BY ten_loai ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết 1 loại bài viết theo ID
    public function getCategoryById($id) {
        $sql = "SELECT * FROM loai_bai_viet WHERE ma_loai = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>