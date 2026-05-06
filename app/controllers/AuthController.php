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

        // CSRF Token Verification
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $this->errors[] = 'CSRF token không hợp lệ. Vui lòng thử lại.';
            $errors = $this->errors;
            $view_content = dirname(__FILE__) . '/../views/pages/auth/register.php';
            $pageTitle = 'Đăng ký';
            require_once dirname(__FILE__) . '/../views/template.php';
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

        // CSRF Token Verification
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $this->errors[] = 'CSRF token không hợp lệ. Vui lòng thử lại.';
            $errors = $this->errors;
            $view_content = dirname(__FILE__) . '/../views/pages/auth/login.php';
            $pageTitle = 'Đăng nhập';
            require_once dirname(__FILE__) . '/../views/template.php';
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
            
            // Bảo mật session
            session_regenerate_id(true);
            
            // Đăng nhập thành công - Lưu vào Session
            $_SESSION['userid'] = $result['user']['userid'];
            $_SESSION['username'] = $result['user']['username'];
            $_SESSION['ho_va_ten_dem'] = $result['user']['ho_va_ten_dem'];
            $_SESSION['ten'] = $result['user']['ten'];
            $_SESSION['user_role'] = $result['user']['role'];
            
            // Session timeout
            $_SESSION['login_time'] = time();
            $_SESSION['last_activity'] = time();
            $_SESSION['session_timeout'] = 3600; // Thời gian của 1 session

            // Phân quyền role
            if ($result['user']['role'] === 'administrator') {
                header('Location: ' . BASE_URL . '/public/index.php?page=admin');
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
        // Khởi tạo session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Clear tất cả dữ liệu session hiện tại
        $_SESSION = array();
        
        // Xóa PHPSESSID cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 1000,  // Đặt thời gian trong quá khứ để cookie hết hạn
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
        // Xóa session trên server
        session_destroy();
        
        header('Location: ' . BASE_URL . '/public/index.php?page=home');
        exit;
    }
    
    // Kiểm tra session timeout
    
    public function checkSessionTimeout() {
        if (!isset($_SESSION['userid'])) {
            return true;
        }
        
        $current_time = time();
        $last_activity = isset($_SESSION['last_activity']) ? $_SESSION['last_activity'] : null;
        $timeout = isset($_SESSION['session_timeout']) ? $_SESSION['session_timeout'] : 3600; // Mặc định: 3600s = 1 tiếng
        
        // Timeout
        if ($last_activity && ($current_time - $last_activity) > $timeout) {
            $this->logout();
            return false; // Session hết hạn
        }
        
        // Cập nhật timestamp của hoạt động cuối cùng
        $_SESSION['last_activity'] = $current_time;
        return true; // Session still valid
    }

    // Cookie bảo mật - chỉ được gọi một lần khi web khởi động
    
    public static function configureSessionSecurity() {
        session_set_cookie_params([
            'lifetime' => 0,       // Thời gian của cookie -> lúc test chỉnh thành 3600*24 (= 1 ngày)
            'path' => '/',
            'domain' => '',        // Tên miền hiện tại
            'secure' => false,     // Nếu trong HTTPS -> đặt về true
            'httponly' => true,    // Ngăn chặn JavaScript truy cập vào session cookie
            'samesite' => 'Lax'    // Ngăn chặn CSRF attacks
        ]);
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