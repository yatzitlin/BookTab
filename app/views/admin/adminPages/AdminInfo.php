<?php
require_once __DIR__ . '/../../../models/ThongTinModel.php';
$thongTinModel = new ThongTinModel($dbConnection);
$allThongTin = array_filter($thongTinModel->getAllWithChiTiet(), function ($row) {
    return $row['loai_thong_tin'] !== 'about';
});

$successMsg = $_SESSION['admin_info_success'] ?? '';
$errorMsg = $_SESSION['admin_info_error'] ?? '';
unset($_SESSION['admin_info_success'], $_SESSION['admin_info_error']);
?>

<div class="main-content-inner">
    <div class="card mt-4">
        <div class="card-body">
            <h4 class="header-title mb-4">Quản lý thông tin trang web</h4>

            <?php if (!empty($successMsg)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($successMsg, ENT_QUOTES, 'UTF-8'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (!empty($errorMsg)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($errorMsg, ENT_QUOTES, 'UTF-8'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Update/Delete forms -->
            <?php foreach ($allThongTin as $row): ?>
                <form id="update-<?php echo (int)$row['ma_thong_tin']; ?>" method="POST"
                      action="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=info">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="act" value="update_info">
                    <input type="hidden" name="id" value="<?php echo (int)$row['ma_thong_tin']; ?>">
                    <input type="hidden" name="type" value="<?php echo htmlspecialchars($row['type'], ENT_QUOTES, 'UTF-8'); ?>">
                </form>
                <form id="delete-<?php echo (int)$row['ma_thong_tin']; ?>" method="POST"
                      action="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=info"
                      onsubmit="return confirm('Xoá mục «<?php echo htmlspecialchars($row['loai_thong_tin'], ENT_QUOTES, 'UTF-8'); ?>»?')">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="act" value="delete_info">
                    <input type="hidden" name="id" value="<?php echo (int)$row['ma_thong_tin']; ?>">
                </form>
            <?php endforeach; ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th style="width:160px">Loại</th>
                            <th style="width:80px">Kiểu</th>
                            <th>Nội dung</th>
                            <th style="width:110px">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allThongTin as $row): ?>
                            <?php $id = (int)$row['ma_thong_tin']; ?>
                            <tr>
                                <td>
                                    <input type="text" name="loai_thong_tin" class="form-control form-control-sm"
                                           form="update-<?php echo $id; ?>"
                                           value="<?php echo htmlspecialchars($row['loai_thong_tin'], ENT_QUOTES, 'UTF-8'); ?>" required>
                                </td>
                                <td>
                                    <span class="badge <?php echo $row['type'] === 'link' ? 'bg-info' : 'bg-secondary'; ?>">
                                        <?php echo $row['type'] === 'link' ? 'Link' : 'Text'; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php $chiTiet = $row['chi_tiet'] ?? []; ?>
                                    <?php if ($row['type'] === 'link'): ?>
                                        <?php if (empty($chiTiet)) $chiTiet = [['noi_dung' => '', 'url' => '']]; ?>
                                        <div class="link-rows" data-id="<?php echo $id; ?>">
                                            <?php foreach ($chiTiet as $i => $item): ?>
                                                <div class="row g-1 mb-1 link-row">
                                                    <div class="col-5">
                                                        <input type="text" name="chi_tiet[<?php echo $i; ?>][noi_dung]" class="form-control form-control-sm"
                                                               form="update-<?php echo $id; ?>"
                                                               value="<?php echo htmlspecialchars($item['noi_dung'], ENT_QUOTES, 'UTF-8'); ?>"
                                                               placeholder="Tên hiển thị">
                                                    </div>
                                                    <div class="col-5">
                                                        <input type="text" name="chi_tiet[<?php echo $i; ?>][url]" class="form-control form-control-sm"
                                                               form="update-<?php echo $id; ?>"
                                                               value="<?php echo htmlspecialchars($item['url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                               placeholder="URL">
                                                    </div>
                                                    <div class="col-2">
                                                        <button type="button" class="btn btn-outline-danger btn-sm remove-row" title="Xoá dòng">
                                                            <i class="ti-close"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <button type="button" class="btn btn-outline-success btn-sm mt-1 add-row" data-id="<?php echo $id; ?>">
                                            <i class="ti-plus"></i> Thêm nội dung
                                        </button>
                                    <?php else: ?>
                                        <?php if (empty($chiTiet)) $chiTiet = [['noi_dung' => '']]; ?>
                                        <div class="text-rows" data-id="<?php echo $id; ?>">
                                            <?php foreach ($chiTiet as $i => $item): ?>
                                                <div class="row g-1 mb-1 text-row">
                                                    <div class="col-10">
                                                        <input type="text" name="chi_tiet[<?php echo $i; ?>][noi_dung]" class="form-control form-control-sm"
                                                               form="update-<?php echo $id; ?>"
                                                               value="<?php echo htmlspecialchars($item['noi_dung'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                               placeholder="Nội dung">
                                                    </div>
                                                    <div class="col-2">
                                                        <button type="button" class="btn btn-outline-danger btn-sm remove-text-row" title="Xoá dòng">
                                                            <i class="ti-close"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <button type="button" class="btn btn-outline-success btn-sm mt-1 add-text-row" data-id="<?php echo $id; ?>">
                                            <i class="ti-plus"></i> Thêm nội dung
                                        </button>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="submit" form="update-<?php echo $id; ?>" class="btn btn-primary btn-sm" title="Lưu">
                                        <i class="ti-save"></i>
                                    </button>
                                    <button type="submit" form="delete-<?php echo $id; ?>" class="btn btn-outline-danger btn-sm" title="Xoá">
                                        <i class="ti-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($allThongTin)): ?>
                            <tr><td colspan="4" class="text-center text-muted">Chưa có dữ liệu</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <hr class="my-4">
            <h5 class="mb-3">Thêm trường thông tin mới</h5>
            <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=info">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="act" value="add_info">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Tên loại</label>
                        <input type="text" name="loai_thong_tin" class="form-control" placeholder="vd: hotline" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Kiểu</label>
                        <select name="type" class="form-select" id="newType">
                            <option value="text">Text</option>
                            <option value="link">Link</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="newTextField">
                        <label class="form-label">Nội dung</label>
                        <div id="newTextRows">
                            <div class="row g-1 mb-1">
                                <div class="col-12">
                                    <input type="text" name="chi_tiet[0][noi_dung]" class="form-control form-control-sm" placeholder="Nội dung...">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-success btn-sm mt-1" id="addNewTextRow">
                            <i class="ti-plus"></i> Thêm nội dung
                        </button>
                    </div>
                    <div class="col-md-6 d-none" id="newLinkField">
                        <label class="form-label">Thông tin chi tiết</label>
                        <div id="newLinkRows">
                            <div class="row g-1 mb-1">
                                <div class="col-6">
                                    <input type="text" name="chi_tiet[0][noi_dung]" class="form-control form-control-sm" placeholder="Tên hiển thị">
                                </div>
                                <div class="col-6">
                                    <input type="text" name="chi_tiet[0][url]" class="form-control form-control-sm" placeholder="URL">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-success btn-sm mt-1" id="addNewRow">
                            <i class="ti-plus"></i> Thêm nội dung
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti-plus"></i> Thêm mới
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Toggle new field type
document.getElementById('newType').addEventListener('change', function() {
    var isLink = this.value === 'link';
    document.getElementById('newTextField').classList.toggle('d-none', isLink);
    document.getElementById('newLinkField').classList.toggle('d-none', !isLink);
});

// --- Link rows (existing) ---
document.querySelectorAll('.add-row').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var container = this.previousElementSibling;
        var rows = container.querySelectorAll('.link-row');
        var idx = rows.length;
        var id = container.dataset.id;
        var div = document.createElement('div');
        div.className = 'row g-1 mb-1 link-row';
        div.innerHTML =
            '<div class="col-5"><input type="text" name="chi_tiet['+idx+'][noi_dung]" class="form-control form-control-sm" form="update-'+id+'" placeholder="Tên hiển thị"></div>' +
            '<div class="col-5"><input type="text" name="chi_tiet['+idx+'][url]" class="form-control form-control-sm" form="update-'+id+'" placeholder="URL"></div>' +
            '<div class="col-2"><button type="button" class="btn btn-outline-danger btn-sm remove-row" title="Xoá dòng"><i class="ti-close"></i></button></div>';
        container.appendChild(div);
        bindRemoveButtons();
    });
});

// --- Text rows (existing) ---
document.querySelectorAll('.add-text-row').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var container = this.previousElementSibling;
        var rows = container.querySelectorAll('.text-row');
        var idx = rows.length;
        var id = container.dataset.id;
        var div = document.createElement('div');
        div.className = 'row g-1 mb-1 text-row';
        div.innerHTML =
            '<div class="col-10"><input type="text" name="chi_tiet['+idx+'][noi_dung]" class="form-control form-control-sm" form="update-'+id+'" placeholder="Nội dung"></div>' +
            '<div class="col-2"><button type="button" class="btn btn-outline-danger btn-sm remove-text-row" title="Xoá dòng"><i class="ti-close"></i></button></div>';
        container.appendChild(div);
        bindRemoveButtons();
    });
});

// Remove row (both link and text)
function bindRemoveButtons() {
    document.querySelectorAll('.remove-row').forEach(function(btn) {
        btn.onclick = function() {
            var row = this.closest('.link-row');
            var container = row.parentElement;
            if (container.querySelectorAll('.link-row').length > 1) {
                row.remove();
                reindexRows(container);
            }
        };
    });
    document.querySelectorAll('.remove-text-row').forEach(function(btn) {
        btn.onclick = function() {
            var row = this.closest('.text-row');
            var container = row.parentElement;
            if (container.querySelectorAll('.text-row').length > 1) {
                row.remove();
                reindexRows(container);
            }
        };
    });
}
bindRemoveButtons();

function reindexRows(container) {
    container.querySelectorAll('.link-row, .text-row').forEach(function(row, i) {
        row.querySelectorAll('input').forEach(function(input) {
            input.name = input.name.replace(/chi_tiet\[\d+\]/, 'chi_tiet['+i+']');
        });
    });
}

// Add row to new link field
var newLinkIdx = 1;
document.getElementById('addNewRow').addEventListener('click', function() {
    var container = document.getElementById('newLinkRows');
    var div = document.createElement('div');
    div.className = 'row g-1 mb-1';
    div.innerHTML =
        '<div class="col-6"><input type="text" name="chi_tiet['+newLinkIdx+'][noi_dung]" class="form-control form-control-sm" placeholder="Tên hiển thị"></div>' +
        '<div class="col-6"><input type="text" name="chi_tiet['+newLinkIdx+'][url]" class="form-control form-control-sm" placeholder="URL"></div>';
    container.appendChild(div);
    newLinkIdx++;
});

// Add row to new text field
var newTextIdx = 1;
document.getElementById('addNewTextRow').addEventListener('click', function() {
    var container = document.getElementById('newTextRows');
    var div = document.createElement('div');
    div.className = 'row g-1 mb-1';
    div.innerHTML =
        '<div class="col-12"><input type="text" name="chi_tiet['+newTextIdx+'][noi_dung]" class="form-control form-control-sm" placeholder="Nội dung"></div>';
    container.appendChild(div);
    newTextIdx++;
});
</script>
