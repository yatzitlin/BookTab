<?php
// Query data ngay trong view (admin layout đã có $dbConnection trong scope)
require_once dirname(__FILE__) . '/../../../models/ProductModel.php';
$productModel = new ProductModel($dbConnection);

$keyword       = trim($_GET['search'] ?? '');
$p             = max(1, intval($_GET['p'] ?? 1));
$limit         = 15;
$products      = $productModel->getAllProductsAdmin($p, $limit, $keyword);
$totalProducts = $productModel->countProductsAdmin($keyword);
$totalPages    = ceil($totalProducts / $limit);
$categories    = $productModel->getCategories();
?>

<div class="main-content-inner">
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Quản lý sản phẩm</h3>
            <button class="btn btn-primary" onclick="document.getElementById('addForm').classList.toggle('d-none')">
                <i class="fa fa-plus"></i> Thêm sản phẩm
            </button>
        </div>

        <!-- Form thêm sản phẩm (ẩn mặc định) -->
        <div id="addForm" class="card mb-4 d-none">
            <div class="card-body">
                <h5>Thêm sản phẩm mới</h5>
                <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?action=admin_product" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="product_action" value="add">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Tên sản phẩm *</label>
                            <input type="text" name="ten_san_pham" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Giá (VNĐ) *</label>
                            <input type="number" name="gia_san_pham" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Danh mục</label>
                            <select name="ma_loai" class="form-control">
                                <option value="">-- Chọn --</option>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?php echo $c['ma_loai']; ?>">
                                        <?php echo htmlspecialchars($c['ten_loai'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Mô tả</label>
                        <textarea name="mo_ta" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Ảnh sản phẩm</label>
                        <input type="file" name="anh_san_pham" class="form-control" accept="image/jpeg,image/png,image/webp">
                        <small class="text-muted">Chấp nhận JPG, PNG, WebP. Tối đa 2MB.</small>
                    </div>
                    <button type="submit" class="btn btn-success">Lưu</button>
                </form>
            </div>
        </div>

        <!-- Thanh tìm kiếm -->
        <form method="GET" action="<?php echo BASE_URL; ?>/public/index.php" class="mb-3">
            <input type="hidden" name="page" value="admin">
            <input type="hidden" name="admin_action" value="products">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                       value="<?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>"
                       placeholder="Tìm sản phẩm...">
                <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </form>

        <!-- Bảng sản phẩm -->
        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Giá</th>
                            <th>Danh mục</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($products)): ?>
                        <tr><td colspan="7" class="text-center">Không có sản phẩm nào.</td></tr>
                    <?php else: ?>
                        <?php foreach ($products as $pr): ?>
                        <tr>
                            <td><?php echo $pr['ma_san_pham']; ?></td>
                            <td>
                                <?php if (!empty($pr['anh_chinh'])): ?>
                                    <img src="<?php echo BASE_URL . '/public/' . htmlspecialchars($pr['anh_chinh'], ENT_QUOTES, 'UTF-8'); ?>"
                                         style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($pr['ten_san_pham'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo number_format($pr['gia_san_pham']); ?>đ</td>
                            <td><?php echo htmlspecialchars($pr['ten_loai'] ?? '—', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <?php if ($pr['is_active']): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning"
                                        onclick="toggleEdit(<?php echo $pr['ma_san_pham']; ?>)">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?action=admin_product"
                                      style="display:inline"
                                      onsubmit="return confirm('Xóa sản phẩm này?')">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <input type="hidden" name="product_action" value="delete">
                                    <input type="hidden" name="ma_san_pham" value="<?php echo $pr['ma_san_pham']; ?>">
                                    <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <!-- Row sửa (ẩn mặc định) -->
                        <tr id="edit-<?php echo $pr['ma_san_pham']; ?>" class="d-none bg-light">
                            <td colspan="7">
                                <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?action=admin_product">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <input type="hidden" name="product_action" value="edit">
                                    <input type="hidden" name="ma_san_pham" value="<?php echo $pr['ma_san_pham']; ?>">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-3">
                                            <input type="text" name="ten_san_pham" class="form-control form-control-sm"
                                                   value="<?php echo htmlspecialchars($pr['ten_san_pham'], ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="gia_san_pham" class="form-control form-control-sm"
                                                   value="<?php echo $pr['gia_san_pham']; ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <select name="ma_loai" class="form-control form-control-sm">
                                                <?php foreach ($categories as $c): ?>
                                                    <option value="<?php echo $c['ma_loai']; ?>"
                                                        <?php echo ($pr['ma_loai'] == $c['ma_loai']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($c['ten_loai'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select name="is_active" class="form-control form-control-sm">
                                                <option value="1" <?php echo $pr['is_active'] ? 'selected' : ''; ?>>Active</option>
                                                <option value="0" <?php echo !$pr['is_active'] ? 'selected' : ''; ?>>Inactive</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-sm btn-success">Lưu</button>
                                            <button type="button" class="btn btn-sm btn-secondary"
                                                    onclick="toggleEdit(<?php echo $pr['ma_san_pham']; ?>)">Huỷ</button>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Phân trang -->
        <?php if ($totalPages > 1): ?>
        <nav class="mt-3">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo $p <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=products&p=<?php echo $p - 1; ?>&search=<?php echo urlencode($keyword); ?>">«</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo $i === $p ? 'active' : ''; ?>">
                    <a class="page-link" href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=products&p=<?php echo $i; ?>&search=<?php echo urlencode($keyword); ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?php echo $p >= $totalPages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=products&p=<?php echo $p + 1; ?>&search=<?php echo urlencode($keyword); ?>">»</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleEdit(id) {
    document.getElementById('edit-' + id).classList.toggle('d-none');
}
</script>
