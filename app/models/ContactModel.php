<?php
class ContactModel {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function insert($name, $email, $message) {
        $sql = "INSERT INTO lien_he (ho_va_ten, email, noi_dung)
                VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $email, $message]);
    }

    public function getAll() {
        $sql = "SELECT * FROM lien_he ORDER BY thoi_gian_tao DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE lien_he SET trang_thai=? WHERE ma_lien_he=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    public function delete($id) {
        $sql = "DELETE FROM lien_he WHERE ma_lien_he=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>