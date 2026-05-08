<?php

class ThongTinModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getByLoai($loai) {
        $sql = "SELECT ma_thong_tin, noi_dung, hinh_anh_nen, so_thu_tu
                FROM thong_tin
                WHERE loai = :loai AND trang_thai = 'active'
                ORDER BY so_thu_tu ASC, ma_thong_tin ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['loai' => $loai]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFirstByLoai($loai) {
        $sql = "SELECT ma_thong_tin, noi_dung, hinh_anh_nen
                FROM thong_tin
                WHERE loai = :loai AND trang_thai = 'active'
                ORDER BY so_thu_tu ASC, ma_thong_tin ASC
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['loai' => $loai]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getById($id) {
        $sql = "SELECT * FROM thong_tin WHERE ma_thong_tin = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updateNoiDung($id, $noiDung) {
        $sql = "UPDATE thong_tin SET noi_dung = :noi_dung, ngay_cap_nhat = NOW() WHERE ma_thong_tin = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['noi_dung' => $noiDung, 'id' => $id]);
    }
}
