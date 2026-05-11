<?php
class BaseController {
    protected $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    protected function loadModel($modelName) {
        $modelPath = dirname(__FILE__) . '/../models/' . $modelName . '.php';

        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $modelName($this->db);
        } else {
            die("Lỗi: Không tìm thấy file Model $modelName");
        }
    }
    protected function view($path, $data = []) {
        extract($data);
        require "../app/views/$path.php";
    }
}
?>