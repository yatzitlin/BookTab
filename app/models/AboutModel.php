<?php

class AboutModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getActiveSections() {
        $sql = "SELECT ma_section, tieu_de, mo_ta_ngan, noi_dung, hinh_anh_url, so_thu_tu
            FROM gioi_thieu
                WHERE trang_thai = 'active'
                ORDER BY so_thu_tu ASC, ma_section ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
