<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'booktab';
    private $user = 'root';
    private $password = '';
    private $pdo;

    /**
     * Kết nối Database sử dụng PDO
     */
    public function connect() {
        try {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8mb4';
            
            $this->pdo = new PDO(
                $dsn,
                $this->user,
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
                )
            );
            
            return $this->pdo;
        } catch (PDOException $e) {
            die('Lỗi kết nối database: ' . $e->getMessage());
        }
    }

    /**
     * Chuẩn bị và thực thi Prepared Statement
     * 
     * @param string $sql - Câu lệnh SQL (với ? hoặc :param)
     * @param array $params - Mảng tham số
     * @return bool|PDOStatement
     */
    public function prepare($sql) {
        return $this->pdo->prepare($sql);
    }

    // Lấy 1 hàng dữ liệu
    public function fetch($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Database Error: ' . $e->getMessage());
        }
    }

    // Lấy các hàng dữ liệu
    public function fetchAll($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Database Error: ' . $e->getMessage());
        }
    }

    // Thêm, xóa, sửa
    public function execute($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception('Database Error: ' . $e->getMessage());
        }
    }

    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }

    // Ngắt kết nối
    public function closeConnection() {
        $this->pdo = null;
    }
}
 
?>