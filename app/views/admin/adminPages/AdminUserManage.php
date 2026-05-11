<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/simple-datatables@10/dist/style.min.css">

<div class="row px-4">
    <div class="col-12 mt-5">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="header-title mb-0">Quản lý người dùng</h4>
                    <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#modalCreateUser">
                        <i class="ti-plus"></i> Thêm người dùng
                    </button>
                </div>

                <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
                    <div class="alert alert-success">Thao tác thành công.</div>
                <?php elseif (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
                    <div class="alert alert-danger">Thao tác thất bại hoặc dữ liệu không hợp lệ.</div>
                <?php endif; ?>

                <?php $currentRoleFilter = $currentRoleFilter ?? 'all'; ?>
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a 
                            class="nav-link font-weight-bold <?php echo ($currentRoleFilter === 'all') ? 'active' : 'text-secondary'; ?>"
                            href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=users&role=all"
                        >
                            Tất cả
                        </a>
                    </li>
                    <li class="nav-item">
                        <a 
                            class="nav-link font-weight-bold <?php echo ($currentRoleFilter === 'administrator') ? 'active' : 'text-secondary'; ?>"
                            href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=users&role=administrator"
                        >
                            Admin
                        </a>
                    </li>
                    <li class="nav-item">
                        <a 
                            class="nav-link font-weight-bold <?php echo ($currentRoleFilter === 'member') ? 'active' : 'text-secondary'; ?>"
                            href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=users&role=member"
                        >
                            User
                        </a>
                    </li>
                </ul>

                <div class="data-tables datatable-dark">
                    <table id="userDataTable" class="text-center">
                        <thead class="text-capitalize">
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Họ và tên</th>
                                <th>Số điện thoại</th>
                                <th>Vai trò</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo (int) $user['userid']; ?></td>
                                        <td class="text-left font-weight-bold"><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td class="text-left"><?php echo htmlspecialchars(trim(($user['ho_va_ten_dem'] ?? '') . ' ' . ($user['ten'] ?? ''))); ?></td>
                                        <td><?php echo htmlspecialchars($user['so_dien_thoai'] ?? ''); ?></td>
                                        <td>
                                            <?php if (($user['user_role'] ?? 'member') === 'administrator'): ?>
                                                <span class="badge bg-primary text-white">Admin</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary text-white">Member</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (($user['trang_thai'] ?? 'inactive') === 'active'): ?>
                                                <span class="badge bg-success text-white">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($user['ngay_tao'] ?? ''); ?></td>
                                        <td>
                                            <ul class="d-flex gap-3 justify-content-center mb-0">
                                                <li class="mr-2">
                                                    <a href="javascript:void(0);" class="text-secondary"
                                                       title="Sửa"
                                                       onclick='openEditUserModal(<?php echo json_encode([
                                                            "userid" => (int) $user["userid"],
                                                            "username" => $user["username"],
                                                            "ho_va_ten_dem" => $user["ho_va_ten_dem"],
                                                            "ten" => $user["ten"],
                                                            "so_dien_thoai" => $user["so_dien_thoai"],
                                                            "trang_thai" => $user["trang_thai"],
                                                            "user_role" => $user["user_role"]
                                                       ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>
                                                        <i class="fa-solid fa-edit"></i>
                                                    </a>
                                                </li>

                                                <li class="mr-2">
                                                    <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=user_toggle_status&id=<?php echo (int) $user['userid']; ?>"
                                                       class="text-secondary"
                                                       title="<?php echo (($user['trang_thai'] ?? 'inactive') === 'active') ? 'Vô hiệu hóa' : 'Kích hoạt'; ?>">
                                                        <i class="fa-solid <?php echo (($user['trang_thai'] ?? 'inactive') === 'active') ? 'fa-user-slash' : 'fa-user-check'; ?>"></i>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="javascript:void(0);"
                                                       class="text-danger"
                                                       title="Xóa"
                                                       onclick="setDeleteUserUrl('<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=user_delete&id=<?php echo (int) $user['userid']; ?>')"
                                                       data-bs-toggle="modal" data-bs-target="#modalDeleteUserConfirm">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCreateUser" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center w-100">
                <h5 class="modal-title mb-0">Thêm người dùng mới</h5>
                <button type="button" class="close ms-auto" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=user_store" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                    <div class="form-row">
                        <div class="col-md-6 form-group">
                            <label class="col-form-label">Username <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="username" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="col-form-label">Mật khẩu <span class="text-danger">*</span></label>
                            <input class="form-control" type="password" name="mat_khau" minlength="6" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 form-group">
                            <label class="col-form-label">Họ và tên đệm <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="ho_va_ten_dem" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="col-form-label">Tên <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="ten" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4 form-group">
                            <label class="col-form-label">Số điện thoại</label>
                            <input class="form-control" type="text" name="so_dien_thoai">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="col-form-label">Vai trò</label>
                            <select class="custom-select" name="user_role">
                                <option value="member">Member</option>
                                <option value="administrator">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="col-form-label">Trạng thái</label>
                            <select class="custom-select" name="trang_thai">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-right mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditUser" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center w-100">
                <h5 class="modal-title mb-0">Chỉnh sửa người dùng</h5>
                <button type="button" class="close ms-auto" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=user_update" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="userid" id="edit_userid">

                    <div class="form-row">
                        <div class="col-md-6 form-group">
                            <label class="col-form-label">Username <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="username" id="edit_username" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="col-form-label">Mật khẩu mới (để trống nếu giữ nguyên)</label>
                            <input class="form-control" type="password" name="mat_khau" minlength="6">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 form-group">
                            <label class="col-form-label">Họ và tên đệm <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="ho_va_ten_dem" id="edit_ho" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="col-form-label">Tên <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="ten" id="edit_ten" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4 form-group">
                            <label class="col-form-label">Số điện thoại</label>
                            <input class="form-control" type="text" name="so_dien_thoai" id="edit_phone">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="col-form-label">Vai trò</label>
                            <select class="custom-select" name="user_role" id="edit_role">
                                <option value="member">Member</option>
                                <option value="administrator">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="col-form-label">Trạng thái</label>
                            <select class="custom-select" name="trang_thai" id="edit_status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-right mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDeleteUserConfirm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa người dùng</h5>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fa-solid fa-triangle-exclamation text-warning mb-3" style="font-size: 3rem;"></i>
                <p class="mb-0">Bạn có muốn xóa người dùng này không?</p>
                <p class="text-danger font-weight-bold mb-0">Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="#" id="btnConfirmDeleteUser" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
</div>

<script>
    function setDeleteUserUrl(url) {
        document.getElementById('btnConfirmDeleteUser').href = url;
    }

    function openEditUserModal(user) {
        document.getElementById('edit_userid').value = user.userid || '';
        document.getElementById('edit_username').value = user.username || '';
        document.getElementById('edit_ho').value = user.ho_va_ten_dem || '';
        document.getElementById('edit_ten').value = user.ten || '';
        document.getElementById('edit_phone').value = user.so_dien_thoai || '';
        document.getElementById('edit_role').value = user.user_role || 'member';
        document.getElementById('edit_status').value = user.trang_thai || 'inactive';

        var modalElement = document.getElementById('modalEditUser');
        var modal = new bootstrap.Modal(modalElement, { backdrop: 'static', keyboard: false });
        modal.show();
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/simple-datatables@10/dist/umd/simple-datatables.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var el = document.getElementById('userDataTable');
        if (el) {
            new simpleDatatables.DataTable(el, {
                perPage: 10,
                searchable: true,
                sortable: true
            });
        }
    });
</script>
