
<?php

class CompanyContactModel
{
    private $conn;
    private $table = "companycontact";

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    // Lấy toàn bộ thông tin liên hệ công ty (thường chỉ 1 dòng)
    public function getContactInfo()
    {
        $sql = "SELECT PhoneNumber, Address, Email FROM " . $this->table . " LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật thông tin liên hệ (dùng cho admin)
    public function updateContactInfo($phone, $address, $email)
    {
        $sql = "UPDATE " . $this->table . "
                SET PhoneNumber = :phone,
                    Address = :address,
                    Email = :email
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':phone' => $phone,
            ':address' => $address,
            ':email' => $email
        ]);
    }
}
?>