<?php
if (class_exists('ThongTinModel')) return;

class ThongTinModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getFirstByLoai($loai) {
        $sql = "SELECT ma_thong_tin, loai_thong_tin, type
                FROM thong_tin
                WHERE loai_thong_tin = :loai
                ORDER BY ma_thong_tin ASC
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['loai' => $loai]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getMultiByLoai(array $loaiList) {
        if (empty($loaiList)) return [];
        $placeholders = implode(',', array_fill(0, count($loaiList), '?'));
        $sql = "SELECT ma_thong_tin, loai_thong_tin, type
                FROM thong_tin
                WHERE loai_thong_tin IN ($placeholders)
                ORDER BY ma_thong_tin ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(array_values($loaiList));
        $results = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $results[$row['loai_thong_tin']] = $row;
        }
        return $results;
    }

    public function getById($id) {
        $sql = "SELECT * FROM thong_tin WHERE ma_thong_tin = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getAll() {
        $sql = "SELECT * FROM thong_tin ORDER BY ma_thong_tin ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($loai, $type) {
        $sql = "INSERT INTO thong_tin (loai_thong_tin, type) VALUES (:loai, :type)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['loai' => $loai, 'type' => $type]);
    }

    public function update($id, $loai, $type) {
        $sql = "UPDATE thong_tin SET loai_thong_tin = :loai, type = :type, ngay_cap_nhat = NOW() WHERE ma_thong_tin = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['loai' => $loai, 'type' => $type, 'id' => $id]);
    }

    public function deleteById($id) {
        $sql = "DELETE FROM thong_tin WHERE ma_thong_tin = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // --- thong_tin_chi_tiet ---

    public function getChiTietByMaThongTin($maThongTin) {
        $sql = "SELECT * FROM thong_tin_chi_tiet WHERE ma_thong_tin = :ma ORDER BY ma_chi_tiet ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['ma' => $maThongTin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFirstNoiDung($loai) {
        $sql = "SELECT ct.noi_dung
                FROM thong_tin_chi_tiet ct
                JOIN thong_tin tt ON tt.ma_thong_tin = ct.ma_thong_tin
                WHERE tt.loai_thong_tin = :loai
                ORDER BY ct.ma_chi_tiet ASC
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['loai' => $loai]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['noi_dung'] : null;
    }

    public function replaceChiTiet($maThongTin, array $items) {
        $this->conn->prepare("DELETE FROM thong_tin_chi_tiet WHERE ma_thong_tin = :id")->execute(['id' => $maThongTin]);
        if (empty($items)) return;
        $stmt = $this->conn->prepare("INSERT INTO thong_tin_chi_tiet (ma_thong_tin, noi_dung, url) VALUES (:ma, :nd, :url)");
        foreach ($items as $item) {
            $nd = trim($item['noi_dung'] ?? '');
            if ($nd === '') continue;
            $url = !empty($item['url']) ? trim($item['url']) : null;
            $stmt->execute(['ma' => $maThongTin, 'nd' => $nd, 'url' => $url]);
        }
    }

    public function getAllWithChiTiet() {
        $all = $this->getAll();
        foreach ($all as &$row) {
            $row['chi_tiet'] = $this->getChiTietByMaThongTin($row['ma_thong_tin']);
        }
        return $all;
    }
}
