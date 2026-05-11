<?php

class UserModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    /**
     * Register
     * 
     * @param string $username - Tên người dùng
    * @param string $email - Email
    * @param string $ho_va_ten_dem - Họ và tên đệm
     * @param string $ten - Tên
     * @param string $mat_khau - Mật khẩu
     * @param string $so_dien_thoai - Số điện thoại (không bắt buộc)
     * @return array - Thành công: ['success' => true, 'userid' => id]
     *                 Thất bại: ['success' => false, 'message' => 'Lỗi gì']
     */
    public function register($username, $email, $ho_va_ten_dem, $ten, $mat_khau, $so_dien_thoai = '') {
        try {
            // 1. Server-side validation
            if (empty($username) || empty($email) || empty($ho_va_ten_dem) || empty($ten) || empty($mat_khau)) {
                return ['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin'];
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => 'Email không hợp lệ'];
            }

            // Kiểm tra độ dài mật khẩu (tối thiểu 6 ký tự)
            if (strlen($mat_khau) < 6) {
                return ['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự'];
            }

            // Kiểm tra độ dài username (3-50 ký tự)
            if (strlen($username) < 3 || strlen($username) > 50) {
                return ['success' => false, 'message' => 'Tên đăng nhập phải từ 3-50 ký tự'];
            }

            // 2. Kiểm tra username và email đã tồn tại chưa
            $sql = "SELECT userid FROM nguoi_dung WHERE username = ? OR email = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$username, $email]);

            if ($stmt->rowCount() > 0) {
                return ['success' => false, 'message' => 'Tên đăng nhập hoặc email này đã được sử dụng'];
            }

            // 3. Mã hóa mật khẩu sử dụng BCRYPT
            $hashed_password = password_hash($mat_khau, PASSWORD_BCRYPT);

            // 4. INSERT vào bảng nguoi_dung
                $sql = "INSERT INTO nguoi_dung (username, email, mat_khau, ho_va_ten_dem, ten, so_dien_thoai, trang_thai, ngay_tao) 
                    VALUES (?, ?, ?, ?, ?, ?, 'active', NOW())";
            $stmt = $this->db->prepare($sql);
                $result = $stmt->execute([$username, $email, $hashed_password, $ho_va_ten_dem, $ten, $so_dien_thoai]);

            if (!$result) {
                return ['success' => false, 'message' => 'Không thể tạo tài khoản'];
            }

            $userid = $this->db->lastInsertId();

            // 5. INSERT vào bảng member (Mặc định rank = NULL)
            $sql = "INSERT INTO member (userid, ten_rank) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userid, null]);

            return [
                'success' => true,
                'message' => 'Đăng ký thành công',
                'userid' => $userid
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()];
        }
    }

    /**
     * Log in
     * 
     * @param string $username - Tên người dùng
     * @param string $mat_khau - Mật khẩu
     * @return array - Thành công: ['success' => true, 'user' => ['userid', 'username', 'ho_va_ten_dem', 'ten', 'role']]
     *                 Thất bại: ['success' => false, 'message' => 'Lỗi gì']
     */
    public function login($username, $mat_khau) {
        try {
            // 1. Server-side validation
            if (empty($username) || empty($mat_khau)) {
                return ['success' => false, 'message' => 'Vui lòng nhập tên đăng nhập và mật khẩu'];
            }

            // 2. Tìm user theo username (Prepared Statement chống SQLi)
            $sql = "SELECT userid, username, mat_khau, ho_va_ten_dem, ten, trang_thai FROM nguoi_dung WHERE username = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            // 3. Kiểm tra user tồn tại
            if (!$user) {
                return ['success' => false, 'message' => 'Tên đăng nhập hoặc mật khẩu không chính xác'];
            }

            // 4. Kiểm tra mật khẩu sử dụng password_verify
            if (!password_verify($mat_khau, $user['mat_khau'])) {
                return ['success' => false, 'message' => 'Tên đăng nhập hoặc mật khẩu không chính xác'];
            }

            // 5. Kiểm tra tài khoản có bị khóa không
            if ($user['trang_thai'] !== 'active') {
                return ['success' => false, 'message' => 'Tài khoản của bạn đã bị khóa'];
            }

            // 6. Xác định role (administrator hay member)
            $role = 'member';
            $sql = "SELECT userid FROM administrator WHERE userid = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$user['userid']]);
            if ($stmt->rowCount() > 0) {
                $role = 'administrator';
            }

            // 7. Mật khẩu đúng - trả về thông tin user
            return [
                'success' => true,
                'message' => 'Đăng nhập thành công',
                'user' => [
                    'userid' => $user['userid'],
                    'username' => $user['username'],
                    'ho_va_ten_dem' => $user['ho_va_ten_dem'],
                    'ten' => $user['ten'],
                    'role' => $role
                ]
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()];
        }
    }

    // Lấy thông tin User
    public function getUserById($userid) {
        try {
            $sql = "SELECT userid, username, ho_va_ten_dem, ten, so_dien_thoai, trang_thai, email, ngay_tao, avatar_url FROM nguoi_dung WHERE userid = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userid]);
            return $stmt->fetch();
        } catch (Exception $e) {
            return null;
        }
    }

    // Kiểm tra User có tồn tại không
    public function usernameExists($username) {
        try {
            $sql = "SELECT userid FROM nguoi_dung WHERE username = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$username]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    // Lấy role của User
    public function getUserRole($userid) {
        try {
            $sql = "SELECT userid FROM administrator WHERE userid = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userid]);
            if ($stmt->rowCount() > 0) {
                return 'administrator';
            }
            return 'member';
        } catch (Exception $e) {
            return 'member';
        }
    }

    // Update thông tin User
    public function updateUser($userid, $ho_va_ten_dem, $ten, $so_dien_thoai, $email) {
        try {
            $sql = "UPDATE nguoi_dung SET ho_va_ten_dem = ?, ten = ?, so_dien_thoai = ?, email = ? WHERE userid = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$ho_va_ten_dem, $ten, $so_dien_thoai, $email, $userid]);
        } catch (Exception $e) {
            return false;
        }
    }

    // Thay đổi mật khẩu
    public function changePassword($userid, $mat_khau_cu, $mat_khau_moi) {
        try {
            // Lấy mật khẩu hiện tại
            $sql = "SELECT mat_khau FROM nguoi_dung WHERE userid = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userid]);
            $user = $stmt->fetch();

            if (!$user) {
                return ['success' => false, 'message' => 'User không tồn tại'];
            }

            // Kiểm tra mật khẩu cũ
            if (!password_verify($mat_khau_cu, $user['mat_khau'])) {
                return ['success' => false, 'message' => 'Mật khẩu cũ không chính xác'];
            }

            // Kiểm tra mật khẩu mới
            if (strlen($mat_khau_moi) < 6) {
                return ['success' => false, 'message' => 'Mật khẩu mới phải có ít nhất 6 ký tự'];
            }

            // Cập nhật mật khẩu mới
            $hashed_password = password_hash($mat_khau_moi, PASSWORD_BCRYPT);
            $sql = "UPDATE nguoi_dung SET mat_khau = ? WHERE userid = ?";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$hashed_password, $userid]);

            return ['success' => true, 'message' => 'Đổi mật khẩu thành công'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()];
        }
    }

    public function updateAvatar($userid, $newAvatar)
    {
        try {
            // 1. Lấy avatar cũ
            $sql = "SELECT avatar_url FROM nguoi_dung WHERE userid = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userid]);
            $old = $stmt->fetch();

            // 2. Xoá file cũ nếu tồn tại
            if (!empty($old['avatar_url'])) {
                $oldPath = __DIR__ . '/../../public/uploads/avatars/' . $old['avatar_url'];

                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // 3. Update DB avatar mới
            $sql = "UPDATE nguoi_dung SET avatar_url = ? WHERE userid = ?";
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([$newAvatar, $userid]);

        } catch (Exception $e) {
            return false;
        }
    }

    // ===============================
    // Admin User Manage
    // ===============================

    public function getAllUsersForAdmin($roleFilter = 'all') {
        $sql = "SELECT u.userid, u.username, u.ho_va_ten_dem, u.ten, u.so_dien_thoai, u.trang_thai, u.ngay_tao,
                       CASE WHEN a.userid IS NOT NULL THEN 'administrator' ELSE 'member' END AS user_role
                FROM nguoi_dung u
                LEFT JOIN administrator a ON a.userid = u.userid";

        $params = [];

        if ($roleFilter === 'administrator') {
            $sql .= " WHERE a.userid IS NOT NULL";
        } elseif ($roleFilter === 'member') {
            $sql .= " WHERE a.userid IS NULL";
        }

        $sql .= " ORDER BY u.userid DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserByIdForAdmin($userId) {
        $sql = "SELECT u.userid, u.username, u.ho_va_ten_dem, u.ten, u.so_dien_thoai, u.trang_thai,
                       CASE WHEN a.userid IS NOT NULL THEN 'administrator' ELSE 'member' END AS user_role
                FROM nguoi_dung u
                LEFT JOIN administrator a ON a.userid = u.userid
                WHERE u.userid = ?
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function isPhoneColumnExists() {
        try {
            $stmt = $this->db->query("SHOW COLUMNS FROM nguoi_dung LIKE 'so_dien_thoai'");
            return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return true;
        }
    }

    public function createUserByAdmin($data) {
        try {
            $this->db->beginTransaction();

            $hasPhone = $this->isPhoneColumnExists();

            if ($hasPhone) {
                $sql = "INSERT INTO nguoi_dung (username, mat_khau, ho_va_ten_dem, ten, so_dien_thoai, trang_thai, ngay_tao)
                        VALUES (?, ?, ?, ?, ?, ?, NOW())";
                $params = [
                    $data['username'],
                    $data['mat_khau'],
                    $data['ho_va_ten_dem'],
                    $data['ten'],
                    $data['so_dien_thoai'],
                    $data['trang_thai']
                ];
            } else {
                $sql = "INSERT INTO nguoi_dung (username, mat_khau, ho_va_ten_dem, ten, trang_thai, ngay_tao)
                        VALUES (?, ?, ?, ?, ?, NOW())";
                $params = [
                    $data['username'],
                    $data['mat_khau'],
                    $data['ho_va_ten_dem'],
                    $data['ten'],
                    $data['trang_thai']
                ];
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $newUserId = (int) $this->db->lastInsertId();

            if (($data['user_role'] ?? 'member') === 'administrator') {
                $stmtAdmin = $this->db->prepare("INSERT INTO administrator (userid) VALUES (?)");
                $stmtAdmin->execute([$newUserId]);
            } else {
                $stmtMember = $this->db->prepare("INSERT INTO member (userid, ten_rank) VALUES (?, ?)");
                $stmtMember->execute([$newUserId, null]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return false;
        }
    }

    public function updateUserByAdmin($userId, $data) {
        try {
            $this->db->beginTransaction();

            $hasPhone = $this->isPhoneColumnExists();
            $hasNewPassword = !empty($data['mat_khau']);

            if ($hasPhone) {
                if ($hasNewPassword) {
                    $sql = "UPDATE nguoi_dung
                            SET username = ?, ho_va_ten_dem = ?, ten = ?, so_dien_thoai = ?, trang_thai = ?, mat_khau = ?
                            WHERE userid = ?";
                    $params = [
                        $data['username'],
                        $data['ho_va_ten_dem'],
                        $data['ten'],
                        $data['so_dien_thoai'],
                        $data['trang_thai'],
                        $data['mat_khau'],
                        $userId
                    ];
                } else {
                    $sql = "UPDATE nguoi_dung
                            SET username = ?, ho_va_ten_dem = ?, ten = ?, so_dien_thoai = ?, trang_thai = ?
                            WHERE userid = ?";
                    $params = [
                        $data['username'],
                        $data['ho_va_ten_dem'],
                        $data['ten'],
                        $data['so_dien_thoai'],
                        $data['trang_thai'],
                        $userId
                    ];
                }
            } else {
                if ($hasNewPassword) {
                    $sql = "UPDATE nguoi_dung
                            SET username = ?, ho_va_ten_dem = ?, ten = ?, trang_thai = ?, mat_khau = ?
                            WHERE userid = ?";
                    $params = [
                        $data['username'],
                        $data['ho_va_ten_dem'],
                        $data['ten'],
                        $data['trang_thai'],
                        $data['mat_khau'],
                        $userId
                    ];
                } else {
                    $sql = "UPDATE nguoi_dung
                            SET username = ?, ho_va_ten_dem = ?, ten = ?, trang_thai = ?
                            WHERE userid = ?";
                    $params = [
                        $data['username'],
                        $data['ho_va_ten_dem'],
                        $data['ten'],
                        $data['trang_thai'],
                        $userId
                    ];
                }
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            if (($data['user_role'] ?? 'member') === 'administrator') {
                $stmtUpsertAdmin = $this->db->prepare("INSERT IGNORE INTO administrator (userid) VALUES (?)");
                $stmtUpsertAdmin->execute([$userId]);
            } else {
                $stmtDeleteAdmin = $this->db->prepare("DELETE FROM administrator WHERE userid = ?");
                $stmtDeleteAdmin->execute([$userId]);

                $stmtEnsureMember = $this->db->prepare("INSERT IGNORE INTO member (userid, ten_rank) VALUES (?, ?)");
                $stmtEnsureMember->execute([$userId, null]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return false;
        }
    }

    public function toggleUserStatus($userId) {
        $sql = "SELECT trang_thai FROM nguoi_dung WHERE userid = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false;
        }

        $newStatus = ($row['trang_thai'] === 'active') ? 'inactive' : 'active';
        $updateStmt = $this->db->prepare("UPDATE nguoi_dung SET trang_thai = ? WHERE userid = ?");
        return $updateStmt->execute([$newStatus, $userId]);
    }

    public function deleteUserByAdmin($userId) {
        $stmt = $this->db->prepare("DELETE FROM nguoi_dung WHERE userid = ?");
        return $stmt->execute([$userId]);
    }
}
?>