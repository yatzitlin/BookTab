<?php

// Register, Login, Logout

require_once dirname(__FILE__) . '/../models/UserModel.php';

class AuthController {
    private $userModel;
    private $errors = [];
    private $success_message = '';

    public function __construct() {
        $this->userModel = new UserModel();
    }

    // Form Register

    public function showRegisterForm() {
        $errors = $this->errors;
        $view_content = dirname(__FILE__) . '/../views/pages/auth/register.php';
        $pageTitle = 'Đăng ký';
        require_once dirname(__FILE__) . '/../views/template.php';
    }

    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showRegisterForm();
            return;
        }

        $username = trim($_POST['username'] ?? '');
        $ho_va_ten_dem = trim($_POST['ho_va_ten_dem'] ?? '');
        $ten = trim($_POST['ten'] ?? '');
        $mat_khau = $_POST['mat_khau'] ?? '';
        $mat_khau_confirm = $_POST['mat_khau_confirm'] ?? '';
        $so_dien_thoai = trim($_POST['so_dien_thoai'] ?? '');

        // Validate phía Server
        if (empty($username) || empty($ho_va_ten_dem) || empty($ten) || empty($mat_khau) || empty($mat_khau_confirm)) {
            $this->errors[] = 'Vui lòng điền đầy đủ thông tin';
        }

        if (!empty($mat_khau) && !empty($mat_khau_confirm) && $mat_khau !== $mat_khau_confirm) {
            $this->errors[] = 'Mật khẩu và xác nhận mật khẩu không khớp';
        }

        if (!empty($this->errors)) {
            $errors = $this->errors;
            $view_content = dirname(__FILE__) . '/../views/pages/auth/register.php';
            $pageTitle = 'Đăng ký';
            require_once dirname(__FILE__) . '/../views/template.php';
            return;
        }

        // Gọi UserModel để đăng ký
        $result = $this->userModel->register($username, $ho_va_ten_dem, $ten, $mat_khau, $so_dien_thoai);

        if ($result['success']) {
            // Đăng ký thành công
            header('Location: ' . BASE_URL . '/public/index.php?action=login&registered=1');
            exit;
        } else {
            $this->errors[] = $result['message'];
            $errors = $this->errors;
            $view_content = dirname(__FILE__) . '/../views/pages/auth/register.php';
            $pageTitle = 'Đăng ký';
            require_once dirname(__FILE__) . '/../views/template.php';
        }
    }

    
    // Form Login
    
    public function showLoginForm() {
        $errors = $this->errors;
        $view_content = dirname(__FILE__) . '/../views/pages/auth/login.php';
        $pageTitle = 'Đăng nhập';
        require_once dirname(__FILE__) . '/../views/template.php';
    }

    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showLoginForm();
            return;
        }

        $username = trim($_POST['username'] ?? '');
        $mat_khau = $_POST['mat_khau'] ?? '';

        // Validate phía Server
        if (empty($username) || empty($mat_khau)) {
            $this->errors[] = 'Vui lòng nhập tên đăng nhập và mật khẩu';
        }

        if (!empty($this->errors)) {
            $errors = $this->errors;
            $view_content = dirname(__FILE__) . '/../views/pages/auth/login.php';
            $pageTitle = 'Đăng nhập';
            require_once dirname(__FILE__) . '/../views/template.php';
            return;
        }

        // Gọi UserModel để đăng nhập
        $result = $this->userModel->login($username, $mat_khau);

        if ($result['success']) {
            
            // Đăng nhập thành công - Lưu vào Session
            $_SESSION['userid'] = $result['user']['userid'];
            $_SESSION['username'] = $result['user']['username'];
            $_SESSION['ho_va_ten_dem'] = $result['user']['ho_va_ten_dem'];
            $_SESSION['ten'] = $result['user']['ten'];
            $_SESSION['user_role'] = $result['user']['role'];

            // Phân quyền role
            if ($result['user']['role'] === 'administrator') {
                header('Location: ' . BASE_URL . '/admin/dashboard.php');
            } else {
                header('Location: ' . BASE_URL . '/public/index.php?page=home');
            }
            exit;
        } else {
            $this->errors[] = $result['message'];
            $errors = $this->errors;
            $view_content = dirname(__FILE__) . '/../views/pages/auth/login.php';
            $pageTitle = 'Đăng nhập';
            require_once dirname(__FILE__) . '/../views/template.php';
        }
    }

    // Log out

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/public/index.php?page=home');
        exit;
    }

    // Errors

    public function getErrors() {
        return $this->errors;
    }

    public function setErrors($errors) {
        $this->errors = $errors;
    }
}
 
?>