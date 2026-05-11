<?php
require_once __DIR__ . '/../../../models/ThongTinModel.php';
$thongTinModel = new ThongTinModel($dbConnection);
$allThongTin = array_values(array_filter($thongTinModel->getAllWithChiTiet(), function($r) {
    return $r['loai_thong_tin'] !== 'about';
}));

$successMsg = $_SESSION['admin_info_success'] ?? '';
$errorMsg   = $_SESSION['admin_info_error']   ?? '';
unset($_SESSION['admin_info_success'], $_SESSION['admin_info_error']);

function infoValidate($loai, $type, $chiTiet) {
    $errors = [];
    $loai = trim($loai);
    if ($loai === '') $errors[] = 'Tên loại không được để trống.';
    elseif (mb_strlen($loai) > 80) $errors[] = 'Tên loại tối đa 80 ký tự.';
    if (!in_array($type, ['text','link'])) $errors[] = 'Kiểu dữ liệu không hợp lệ.';
    foreach ($chiTiet as $item) {
        $nd = trim($item['noi_dung'] ?? '');
        if ($nd !== '' && mb_strlen($nd) > 500) { $errors[] = 'Nội dung mỗi dòng tối đa 500 ký tự.'; break; }
        if ($type === 'link' && !empty($item['url'])) {
            $url = trim($item['url']);
            if ($url !== '' && !filter_var($url, FILTER_VALIDATE_URL) && !preg_match('/^(mailto:|tel:|\/|#)/', $url))
                $errors[] = 'URL "' . htmlspecialchars($url) . '" không hợp lệ.';
        }
    }
    return $errors;
}
?>
<style>
@keyframes fadeSlideUp {
    from { opacity:0; transform:translateY(16px); }
    to   { opacity:1; transform:translateY(0); }
}
.info-card {
    border:none;
    border-radius:12px;
    box-shadow:0 2px 12px rgba(0,0,0,.07);
    transition:box-shadow .25s, transform .25s;
    animation: fadeSlideUp .35s ease both;
}
.info-card:hover { box-shadow:0 6px 24px rgba(0,0,0,.13); transform:translateY(-2px); }
.info-card .card-header {
    border-radius:12px 12px 0 0 !important;
    background: linear-gradient(135deg,#f8f9fa 0%,#e9ecef 100%);
    border-bottom:1px solid rgba(0,0,0,.06);
    padding:.75rem 1.1rem;
}
.badge-type { font-size:.7rem; padding:3px 8px; border-radius:20px; letter-spacing:.3px; }
.chip {
    display:inline-flex; align-items:center; gap:5px;
    background:#fff; border:1px solid #dee2e6;
    border-radius:20px; padding:3px 10px; font-size:.82rem;
    margin:2px; transition:background .2s;
}
.chip a { color:inherit; text-decoration:none; }
.chip a:hover { text-decoration:underline; }
.chip-link { background:#e7f3ff; border-color:#b6d4fe; }
.drag-handle { cursor:grab; opacity:.4; }
.drag-handle:active { cursor:grabbing; }
.row-anim-enter { animation: fadeSlideUp .2s ease both; }
.info-card:nth-child(1){animation-delay:.04s}
.info-card:nth-child(2){animation-delay:.08s}
.info-card:nth-child(3){animation-delay:.12s}
.info-card:nth-child(4){animation-delay:.16s}
.info-card:nth-child(5){animation-delay:.20s}
.info-card:nth-child(6){animation-delay:.24s}
.info-card:nth-child(7){animation-delay:.28s}
.info-card:nth-child(8){animation-delay:.32s}
.info-card:nth-child(9){animation-delay:.36s}
.info-card:nth-child(10){animation-delay:.40s}
.add-card { border:2px dashed #ced4da; border-radius:12px; background:#fafbfc; transition:border-color .2s, background .2s; }
.add-card:hover { border-color:#0d6efd; background:#f0f4ff; }
.field-error { font-size:.78rem; color:#dc3545; margin-top:2px; display:none; }
.field-error.show { display:block; }
.is-invalid-custom { border-color:#dc3545 !important; box-shadow:0 0 0 .2rem rgba(220,53,69,.15) !important; }
#toast-container { position:fixed; bottom:1.5rem; right:1.5rem; z-index:9999; display:flex; flex-direction:column; gap:.5rem; }
</style>

<div id="toast-container"></div>

<div class="main-content-inner">

<?php if ($successMsg): ?>
<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
    <i class="ti-check-box me-1"></i><?php echo htmlspecialchars($successMsg,ENT_QUOTES,'UTF-8'); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if ($errorMsg): ?>
<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
    <i class="ti-alert me-1"></i><?php echo htmlspecialchars($errorMsg,ENT_QUOTES,'UTF-8'); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mt-4 mb-3">
    <h5 class="mb-0 fw-semibold"><i class="ti-settings me-1 text-primary"></i>Quản lý thông tin trang web</h5>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addInfoModal">
        <i class="ti-plus me-1"></i>Thêm trường mới
    </button>
</div>

<div class="row g-3" id="info-cards-container">
<?php foreach ($allThongTin as $row):
    $id      = (int)$row['ma_thong_tin'];
    $loai    = htmlspecialchars($row['loai_thong_tin'],ENT_QUOTES,'UTF-8');
    $type    = $row['type'];
    $chiTiet = $row['chi_tiet'] ?? [];
?>
<div class="col-md-6 col-xl-4 info-col" data-id="<?php echo $id; ?>">
    <div class="card info-card h-100">
        <div class="card-header d-flex align-items-center gap-2">
            <i class="ti-menu drag-handle me-1" title="Kéo để sắp xếp"></i>
            <span class="fw-semibold text-truncate flex-grow-1" title="<?php echo $loai; ?>"><?php echo $loai; ?></span>
            <span class="badge badge-type <?php echo $type==='link'?'bg-info text-dark':'bg-secondary'; ?>">
                <i class="<?php echo $type==='link'?'ti-link':'ti-text'; ?>"></i> <?php echo $type==='link'?'Link':'Text'; ?>
            </span>
        </div>
        <div class="card-body pb-2">
            <?php if (empty($chiTiet)): ?>
                <span class="text-muted small fst-italic">Chưa có nội dung</span>
            <?php else: ?>
                <div class="d-flex flex-wrap gap-1">
                <?php foreach ($chiTiet as $ct): ?>
                    <?php if ($type==='link' && !empty($ct['url'])): ?>
                    <span class="chip chip-link">
                        <i class="ti-link" style="font-size:.7rem"></i>
                        <a href="<?php echo htmlspecialchars($ct['url'],ENT_QUOTES,'UTF-8'); ?>" target="_blank" rel="noopener">
                            <?php echo htmlspecialchars($ct['noi_dung'],ENT_QUOTES,'UTF-8'); ?>
                        </a>
                    </span>
                    <?php else: ?>
                    <span class="chip">
                        <i class="ti-text" style="font-size:.7rem;opacity:.5"></i>
                        <?php echo htmlspecialchars($ct['noi_dung'],ENT_QUOTES,'UTF-8'); ?>
                    </span>
                    <?php endif; ?>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="card-footer bg-transparent border-top-0 d-flex gap-2 pt-0 pb-2 px-3">
            <button class="btn btn-outline-primary btn-sm flex-grow-1 edit-btn"
                data-id="<?php echo $id; ?>"
                data-loai="<?php echo $loai; ?>"
                data-type="<?php echo $type; ?>"
                data-chitiet='<?php echo json_encode(array_map(fn($c)=>['noi_dung'=>$c['noi_dung'],'url'=>$c['url']??''],$chiTiet),JSON_HEX_APOS|JSON_HEX_QUOT); ?>'>
                <i class="ti-pencil"></i> Sửa
            </button>
            <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=info"
                  class="delete-form" onsubmit="return confirmDelete(this,'<?php echo $loai; ?>')">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']??'',ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="act" value="delete_info">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <button type="submit" class="btn btn-outline-danger btn-sm"><i class="ti-trash"></i></button>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>

<?php if (empty($allThongTin)): ?>
<div class="text-center text-muted py-5">
    <i class="ti-info-alt" style="font-size:2rem"></i>
    <p class="mt-2">Chưa có dữ liệu thông tin. <a href="#" data-bs-toggle="modal" data-bs-target="#addInfoModal">Thêm mới</a></p>
</div>
<?php endif; ?>

<div class="modal fade" id="editInfoModal" tabindex="-1" aria-labelledby="editInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=info" id="editInfoForm" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']??'',ENT_QUOTES,'UTF-8'); ?>">
        <input type="hidden" name="act" value="update_info">
        <input type="hidden" name="id" id="editId">
        <div class="modal-header">
            <h5 class="modal-title" id="editInfoModalLabel"><i class="ti-pencil me-2 text-primary"></i>Chỉnh sửa thông tin</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Tên loại <span class="text-danger">*</span></label>
                    <input type="text" name="loai_thong_tin" id="editLoai" class="form-control" maxlength="80" required placeholder="vd: hotline, email, address...">
                    <div class="field-error" id="editLoaiErr"></div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kiểu</label>
                    <select name="type" id="editType" class="form-select">
                        <option value="text">Text</option>
                        <option value="link">Link</option>
                    </select>
                </div>
            </div>
            <label class="form-label fw-semibold">Nội dung chi tiết</label>
            <div id="editChiTietRows" class="mb-2"></div>
            <button type="button" class="btn btn-outline-success btn-sm" id="editAddRow">
                <i class="ti-plus"></i> Thêm dòng
            </button>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
            <button type="submit" class="btn btn-primary"><i class="ti-save me-1"></i>Lưu thay đổi</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="addInfoModal" tabindex="-1" aria-labelledby="addInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=info" id="addInfoForm" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']??'',ENT_QUOTES,'UTF-8'); ?>">
        <input type="hidden" name="act" value="add_info">
        <div class="modal-header">
            <h5 class="modal-title" id="addInfoModalLabel"><i class="ti-plus me-2 text-success"></i>Thêm trường thông tin mới</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Tên loại <span class="text-danger">*</span></label>
                    <input type="text" name="loai_thong_tin" id="addLoai" class="form-control" maxlength="80" required placeholder="vd: hotline, email, địa chỉ...">
                    <div class="field-error" id="addLoaiErr"></div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kiểu</label>
                    <select name="type" id="addType" class="form-select">
                        <option value="text">Text</option>
                        <option value="link">Link</option>
                    </select>
                </div>
            </div>
            <label class="form-label fw-semibold">Nội dung chi tiết</label>
            <div id="addChiTietRows" class="mb-2">
            </div>
            <button type="button" class="btn btn-outline-success btn-sm" id="addAddRow">
                <i class="ti-plus"></i> Thêm dòng
            </button>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
            <button type="submit" class="btn btn-success"><i class="ti-plus me-1"></i>Thêm mới</button>
        </div>
      </form>
    </div>
  </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
<script>
(function() {
'use strict';

/* -------- Toast -------- */
function toast(msg, type) {
    type = type || 'success';
    var icons = {success:'ti-check-box',danger:'ti-alert',info:'ti-info-alt'};
    var el = document.createElement('div');
    el.className = 'toast show align-items-center text-bg-' + type + ' border-0';
    el.innerHTML = '<div class="d-flex"><div class="toast-body"><i class="' + (icons[type]||'ti-info-alt') + ' me-1"></i>' + msg + '</div>' +
        '<button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="this.closest(\'.toast\').remove()"></button></div>';
    document.getElementById('toast-container').appendChild(el);
    setTimeout(function(){ el.remove(); }, 3500);
}

function buildRow(container, type, idx, val, url) {
    val = val || ''; url = url || '';
    var div = document.createElement('div');
    div.className = 'row g-2 mb-2 detail-row row-anim-enter';
    if (type === 'link') {
        div.innerHTML =
            '<div class="col-5"><input type="text" name="chi_tiet['+idx+'][noi_dung]" class="form-control form-control-sm ct-nd" placeholder="Tên hiển thị" maxlength="500" value="'+escHtml(val)+'"></div>' +
            '<div class="col-5"><input type="url" name="chi_tiet['+idx+'][url]" class="form-control form-control-sm ct-url" placeholder="https://..." value="'+escHtml(url)+'"></div>' +
            '<div class="col-2 d-flex align-items-center"><button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="ti-close"></i></button></div>';
    } else {
        div.innerHTML =
            '<div class="col-10"><input type="text" name="chi_tiet['+idx+'][noi_dung]" class="form-control form-control-sm ct-nd" placeholder="Nội dung" maxlength="500" value="'+escHtml(val)+'"></div>' +
            '<div class="col-2 d-flex align-items-center"><button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="ti-close"></i></button></div>';
    }
    container.appendChild(div);
    div.querySelector('.remove-row').addEventListener('click', function() {
        if (container.querySelectorAll('.detail-row').length > 1) {
            div.style.opacity='0'; div.style.transform='translateX(20px)'; div.style.transition='all .2s';
            setTimeout(function(){ div.remove(); reindexRows(container); }, 200);
        }
    });
}

function reindexRows(container) {
    container.querySelectorAll('.detail-row').forEach(function(row, i) {
        row.querySelectorAll('input').forEach(function(inp) {
            inp.name = inp.name.replace(/chi_tiet\[\d+\]/, 'chi_tiet['+i+']');
        });
    });
}

function clearRows(container) {
    container.innerHTML = '';
}

function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function validateLoai(input, errEl) {
    var v = input.value.trim();
    if (v === '') { showErr(input, errEl, 'Tên loại không được để trống.'); return false; }
    if (v.length > 80) { showErr(input, errEl, 'Tối đa 80 ký tự.'); return false; }
    clearErr(input, errEl); return true;
}
function validateRows(container, type) {
    var ok = true;
    container.querySelectorAll('.ct-url').forEach(function(inp) {
        var v = inp.value.trim();
        if (v && !/^(https?:\/\/|mailto:|tel:|\/|#)/.test(v)) {
            inp.classList.add('is-invalid-custom');
            ok = false;
        } else inp.classList.remove('is-invalid-custom');
    });
    return ok;
}
function showErr(input, errEl, msg) {
    input.classList.add('is-invalid-custom');
    errEl.textContent = msg; errEl.classList.add('show');
}
function clearErr(input, errEl) {
    input.classList.remove('is-invalid-custom');
    errEl.classList.remove('show');
}

var addModal = document.getElementById('addInfoModal');
addModal.addEventListener('show.bs.modal', function() {
    document.getElementById('addInfoForm').reset();
    clearRows(document.getElementById('addChiTietRows'));
    clearErr(document.getElementById('addLoai'), document.getElementById('addLoaiErr'));
    buildRow(document.getElementById('addChiTietRows'), document.getElementById('addType').value, 0, '', '');
});

document.getElementById('addType').addEventListener('change', function() {
    var c = document.getElementById('addChiTietRows');
    clearRows(c);
    buildRow(c, this.value, 0, '', '');
});

document.getElementById('addAddRow').addEventListener('click', function() {
    var c = document.getElementById('addChiTietRows');
    buildRow(c, document.getElementById('addType').value, c.querySelectorAll('.detail-row').length, '', '');
});

document.getElementById('addInfoForm').addEventListener('submit', function(e) {
    var loaiOk = validateLoai(document.getElementById('addLoai'), document.getElementById('addLoaiErr'));
    var rowsOk = validateRows(document.getElementById('addChiTietRows'), document.getElementById('addType').value);
    if (!loaiOk || !rowsOk) { e.preventDefault(); toast('Vui lòng kiểm tra lại thông tin.','danger'); }
});

document.getElementById('addLoai').addEventListener('input', function() {
    validateLoai(this, document.getElementById('addLoaiErr'));
});

var editModal = document.getElementById('editInfoModal');
document.querySelectorAll('.edit-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id      = this.dataset.id;
        var loai    = this.dataset.loai;
        var type    = this.dataset.type;
        var chiTiet = JSON.parse(this.dataset.chitiet || '[]');

        document.getElementById('editId').value   = id;
        document.getElementById('editLoai').value = loai;
        document.getElementById('editType').value = type;
        clearErr(document.getElementById('editLoai'), document.getElementById('editLoaiErr'));

        var c = document.getElementById('editChiTietRows');
        clearRows(c);
        if (chiTiet.length === 0) chiTiet = [{noi_dung:'',url:''}];
        chiTiet.forEach(function(item, i) {
            buildRow(c, type, i, item.noi_dung, item.url || '');
        });

        new bootstrap.Modal(editModal).show();
    });
});

document.getElementById('editType').addEventListener('change', function() {
    var c = document.getElementById('editChiTietRows');
    clearRows(c);
    buildRow(c, this.value, 0, '', '');
});

document.getElementById('editAddRow').addEventListener('click', function() {
    var c = document.getElementById('editChiTietRows');
    buildRow(c, document.getElementById('editType').value, c.querySelectorAll('.detail-row').length, '', '');
});

document.getElementById('editInfoForm').addEventListener('submit', function(e) {
    var loaiOk = validateLoai(document.getElementById('editLoai'), document.getElementById('editLoaiErr'));
    var rowsOk = validateRows(document.getElementById('editChiTietRows'), document.getElementById('editType').value);
    if (!loaiOk || !rowsOk) { e.preventDefault(); toast('Vui lòng kiểm tra lại thông tin.','danger'); }
});

document.getElementById('editLoai').addEventListener('input', function() {
    validateLoai(this, document.getElementById('editLoaiErr'));
});

window.confirmDelete = function(form, loai) {
    return confirm('Xoá trường «' + loai + '»?\nThao tác này không thể hoàn tác.');
};

new Sortable(document.getElementById('info-cards-container'), {
    animation: 200,
    handle: '.drag-handle',
    ghostClass: 'opacity-50'
});

})();
</script>
