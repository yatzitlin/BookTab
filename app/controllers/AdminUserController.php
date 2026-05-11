<?php
require_once __DIR__ . '/BaseController.php';

class AdminUserController extends BaseController {
    private $userModel;

    public function __construct($dbConnection) {
        parent::__construct($dbConnection);
        $this->userModel = $this->loadModel('UserModel');
    }

    public function index() {
        $roleFilter = isset($_GET['role']) ? trim($_GET['role']) : 'all';
        if (!in_array($roleFilter, ['all', 'administrator', 'member'], true)) {
            $roleFilter = 'all';
        }

        $users = $this->userModel->getAllUsersForAdmin($roleFilter);
        $currentRoleFilter = $roleFilter;
        require_once __DIR__ . '/../views/admin/adminLayout.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=users');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $ho = trim($_POST['ho_va_ten_dem'] ?? '');
        $ten = trim($_POST['ten'] ?? '');
        $soDienThoai = trim($_POST['so_dien_thoai'] ?? '');
        $matKhauRaw = $_POST['mat_khau'] ?? '';
        $role = ($_POST['user_role'] ?? 'member') === 'administrator' ? 'administrator' : 'member';
        $trangThai = ($_POST['trang_thai'] ?? 'active') === 'active' ? 'active' : 'inactive';

        if ($username === '' || $ho === '' || $ten === '' || $matKhauRaw === '') {
            header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=users&status=error');
            exit;
        }

        if (strlen($matKhauRaw) < 6) {
            header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=users&status=error');
            exit;
        }

        $data = [
            'username' => $username,
            'ho_va_ten_dem' => $ho,
            'ten' => $ten,
            'so_dien_thoai' => $soDienThoai,
            'mat_khau' => password_hash($matKhauRaw, PASSWORD_BCRYPT),
            'user_role' => $role,
            'trang_thai' => $trangThai
        ];

        $ok = $this->userModel->createUserByAdmin($data);
        header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=users&status=' . ($ok ? 'success' : 'error'));
        exit;
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=users');
            exit;
        }

        $userId = (int) ($_POST['userid'] ?? 0);
        $username = trim($_POST['username'] ?? '');
        $ho = trim($_POST['ho_va_ten_dem'] ?? '');
        $ten = trim($_POST['ten'] ?? '');
        $soDienThoai = trim($_POST['so_dien_thoai'] ?? '');
        $matKhauRaw = trim($_POST['mat_khau'] ?? '');
        $role = ($_POST['user_role'] ?? 'member') === 'administrator' ? 'administrator' : 'member';
        $trangThai = ($_POST['trang_thai'] ?? 'active') === 'active' ? 'active' : 'inactive';

        if ($userId <= 0 || $username === '' || $ho === '' || $ten === '') {
            header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=users&status=error');
            exit;
        }

        $currentUserId = (int) ($_SESSION['userid'] ?? 0);
        if ($userId === $currentUserId && $role !== 'administrator') {
            header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=users&status=error');
            exit;
        }

        $data = [
            'username' => $username,
            'ho_va_ten_dem' => $ho,
            'ten' => $ten,
            'so_dien_thoai' => $soDienThoai,
            'user_role' => $role,
            'trang_thai' => $trangThai,
            'mat_khau' => ''
        ];

        if ($matKhauRaw !== '') {
            if (strlen($matKhauRaw) < 6) {
                header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=users&status=error');
                exit;
            }
            $data['mat_khau'] = password_hash($matKhauRaw, PASSWORD_BCRYPT);
        }

        $ok = $this->userModel->updateUserByAdmin($userId, $data);
        header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=users&status=' . ($ok ? 'success' : 'error'));
        exit;
    }

    public function toggleStatus() {
        $userId = (int) ($_GET['id'] ?? 0);
        $currentUserId = (int) ($_SESSION['userid'] ?? 0);

        if ($userId <= 0 || $userId === $currentUserId) {
            header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=users&status=error');
            exit;
        }

        $ok = $this->userModel->toggleUserStatus($userId);
        header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=users&status=' . ($ok ? 'success' : 'error'));
        exit;
    }

    public function delete() {
        $userId = (int) ($_GET['id'] ?? 0);
        $currentUserId = (int) ($_SESSION['userid'] ?? 0);

        if ($userId <= 0 || $userId === $currentUserId) {
            header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=users&status=error');
            exit;
        }

        $ok = $this->userModel->deleteUserByAdmin($userId);
        header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=users&status=' . ($ok ? 'success' : 'error'));
        exit;
    }
}
?>