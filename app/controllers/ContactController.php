<?php

require_once __DIR__ . '/BaseController.php';

class ContactController extends BaseController
{
    private $thongTinModel;

    public function __construct($dbConnection) {
        parent::__construct($dbConnection);
        $this->thongTinModel = $this->loadModel('ThongTinModel');
    }

    public function getContactInfo() {
        $result = [];

        $result['hotline'] = $this->thongTinModel->getFirstNoiDung('phone');
        $result['email']   = $this->thongTinModel->getFirstNoiDung('email');
        $result['address'] = $this->thongTinModel->getFirstNoiDung('address');

        return $result;
    }


    public function send()
    {
        // Chỉ cho phép POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header("Location: " . BASE_URL . "/public/index.php?page=contact");
            exit;
        }

        // Lấy dữ liệu
        $name = trim($_POST['fullname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $message = trim($_POST['message'] ?? '');

        // Validate
        $errors = [];

        if (empty($name)) {
            $errors[] = "Vui lòng nhập họ tên";
        }

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email không hợp lệ";
        }

        if (empty($message)) {
            $errors[] = "Vui lòng nhập nội dung";
        }

        // Nếu có lỗi
        if (!empty($errors)) {

            $_SESSION['errors'] = $errors;

            header("Location: " . BASE_URL . "/public/index.php?page=contact");
            exit;
        }

        // Load model
        $contactModel = $this->loadModel('ContactModel');

        // Lưu database
        $success = $contactModel->insert(
            $name,
            $email,
            $message
        );

        // Thông báo
        if ($success) {

            $_SESSION['success'] = "Gửi liên hệ thành công";

        } else {

            $_SESSION['errors'] = [
                "Có lỗi xảy ra"
            ];
        }

        // Redirect
        header("Location: " . BASE_URL . "/public/index.php?page=contact");
        exit;
    }
}

?>