<?php

require_once __DIR__ . '/../models/UserModel.php';

class UserController
{
    private $userModel;

    public function __construct($dbConnection)
    {
        $this->userModel = new UserModel($dbConnection);
    }

    /**
     * VIEW
     */
    public function information()
    {
        return $this->userModel->getUserById($_SESSION['userid']);
    }

    /**
     * UPDATE PROFILE
     */
    public function updateProfile()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['userid'])) {

            header("Location: index.php?page=login");
            exit;
        }

        // Chỉ cho phép POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header("Location: index.php?page=profile");
            exit;
        }

        $userid = $_SESSION['userid'];

        // Lấy dữ liệu
        $ho = trim($_POST['ho_va_ten_dem'] ?? '');
        $ten = trim($_POST['ten'] ?? '');
        $phone = trim($_POST['so_dien_thoai'] ?? '');
        $email = trim($_POST['email'] ?? '');

        // Validate rỗng
        if (
            $ho === '' ||
            $ten === '' ||
            $phone === '' ||
            $email === ''
        ) {

            $_SESSION['error'] = "Không được để trống dữ liệu";
            header("Location: index.php?page=profile");
            exit;
        }

        // Validate họ tên
        if (strlen($ho) > 50 || strlen($ten) > 30) {

            $_SESSION['error'] = "Họ tên quá dài";
            header("Location: index.php?page=profile");
            exit;
        }

        // Validate số điện thoại
        if (!preg_match('/^[0-9]{9,11}$/', $phone)) {

            $_SESSION['error'] = "Số điện thoại không hợp lệ";
            header("Location: index.php?page=profile");
            exit;
        }

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $_SESSION['error'] = "Email không hợp lệ";
            header("Location: index.php?page=profile");
            exit;
        }

        // Gọi model
        $result = $this->userModel->updateUser(
            $userid,
            $ho,
            $ten,
            $phone,
            $email
        );

        // Kết quả
        if ($result) {
            $_SESSION['success'] = "Cập nhật thông tin thành công";
        } else {
            $_SESSION['error'] = $result['message'];
        }

        header("Location: index.php?page=profile");
        exit;
    }

    /**
     * CHANGE PASSWORD
     */
    public function changePassword()
    {
        if (!isset($_SESSION['userid'])) {
            header("Location: index.php?page=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $userid = $_SESSION['userid'];

        $old = trim($_POST['old_password'] ?? '');
        $new = trim($_POST['new_password'] ?? '');
        $confirm = trim($_POST['confirm_password'] ?? '');

        // Kiểm tra rỗng
        if ($old === '' || $new === '' || $confirm === '') {

            $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin";

            header("Location: index.php?page=change-password");
            exit;
        }

        // Xác nhận mật khẩu
        if ($new !== $confirm) {

            $_SESSION['error'] = "Mật khẩu xác nhận không khớp";

            header("Location: index.php?page=change-password");
            exit;
        }

        // Gọi model
        $result = $this->userModel->changePassword(
            $userid,
            $old,
            $new
        );

        // Nếu lỗi -> chỉ hiện 1 lỗi
        if (!$result['success']) {

            $_SESSION['error'] = $result['message'];

            header("Location: index.php?page=change-password");
            exit;
        }

        // Thành công
        $_SESSION['success'] = "Đổi mật khẩu thành công";

        header("Location: index.php?page=profile");
        exit;
    }
    /**
     * UPDATE AVATAR
     */
    public function updateAvatar()
    {
        if (!isset($_SESSION['userid'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $file = $_FILES['avatar'] ?? null;

        if (!$file || $file['error'] !== 0) {
            $_SESSION['error'] = "Chưa chọn ảnh";
            header("Location: index.php?page=profile");
            exit;
        }

        $allow = ['image/jpeg', 'image/png', 'image/jpg'];

        if (!in_array($file['type'], $allow)) {
            $_SESSION['error'] = "File không hợp lệ";
            header("Location: index.php?page=profile");
            exit;
        }

        if ($file['size'] > 2 * 1024 * 1024) {
            $_SESSION['error'] = "Ảnh quá lớn (max 2MB)";
            header("Location: index.php?page=profile");
            exit;
        }

        $name = uniqid() . "_" . basename($file['name']);

        $path = __DIR__ . '/../../public/uploads/avatars/';

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        move_uploaded_file($file['tmp_name'], $path . $name);

        // UPDATE DB + xoá ảnh cũ
        $this->userModel->updateAvatar($_SESSION['userid'], $name);

        $_SESSION['success'] = "Cập nhật ảnh thành công";

        header("Location: index.php?page=profile");
        exit;
    }
}