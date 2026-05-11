<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/simple-datatables@10/dist/style.min.css">

<div class="row px-4">
    <div class="col-12 mt-5">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="header-title mb-0">Quản Lý Bình Luận</h4>
                </div>

                <?php if (!empty($_SESSION['admin_comment_success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Thành công!</strong> <?php echo htmlspecialchars($_SESSION['admin_comment_success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['admin_comment_success']); ?>
                <?php endif; ?>

                <?php if (!empty($_SESSION['admin_comment_error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Lỗi!</strong> <?php echo htmlspecialchars($_SESSION['admin_comment_error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['admin_comment_error']); ?>
                <?php endif; ?>

                <ul class="nav nav-tabs mb-4" id="categoryTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold <?php echo $selectedCat === 0 ? 'active' : 'text-secondary'; ?>"
                           href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=comments&cat=0">
                            Tất cả
                        </a>
                    </li>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <li class="nav-item">
                                <a class="nav-link font-weight-bold <?php echo $selectedCat === (int) $cat['ma_loai'] ? 'active' : 'text-secondary'; ?>"
                                   href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=comments&cat=<?php echo (int) $cat['ma_loai']; ?>">
                                    <?php echo htmlspecialchars($cat['ten_loai']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>

                <style>
                    .datatable-dark .datatable-sorter::before {
                        border-top-color: #ffffff !important;
                        opacity: 0.4;
                    }

                    .datatable-dark .datatable-sorter::after {
                        border-bottom-color: #ffffff !important;
                        opacity: 0.4;
                    }

                    .datatable-dark th.datatable-ascending .datatable-sorter::after,
                    .datatable-dark th.datatable-descending .datatable-sorter::before,
                    .datatable-dark th:hover .datatable-sorter::before,
                    .datatable-dark th:hover .datatable-sorter::after {
                        opacity: 1 !important;
                    }

                    .datatable-sorter {
                        padding-right: 20px !important;
                        display: inline-block;
                    }
                </style>

                <div class="data-tables datatable-dark">
                    <table id="dataTable" class="text-center">
                        <thead class="text-capitalize">
                            <tr>
                                <th>STT</th>
                                <th>Nội dung</th>
                                <th>Người gửi</th>
                                <th>Bài viết</th>
                                <th>Thời gian</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($comments)): ?>
                                <?php $stt = 1; foreach ($comments as $comment): ?>
                                    <tr>
                                        <td><?php echo $stt++; ?></td>
                                        <td class="text-left font-weight-bold" style="max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo htmlspecialchars($comment['noi_dung']); ?>">
                                            <?php if (!empty($comment['binh_luan_cha'])): ?>
                                                <span class="badge bg-info text-dark me-1">Trả lời</span>
                                            <?php endif; ?>
                                            <?php echo htmlspecialchars($comment['noi_dung']); ?>
                                        </td>
                                        <td class="text-left font-weight-bold">
                                            <?php echo htmlspecialchars(trim(($comment['ho_va_ten_dem'] ?? '') . ' ' . ($comment['ten'] ?? ''))); ?>
                                        </td>
                                        <td class="text-left font-weight-bold" style="max-width: 240px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <a href="<?php echo BASE_URL; ?>/public/index.php?page=news&news_action=detail&slug=<?php echo urlencode($comment['slug'] ?? ''); ?>" target="_blank" title="<?php echo htmlspecialchars($comment['tieu_de'] ?? ''); ?>">
                                                <?php echo htmlspecialchars($comment['tieu_de'] ?? ''); ?>
                                            </a>
                                        </td>
                                        <td class="text-left" style="font-size: 0.85rem;">
                                            <div><i class="ti-calendar"></i> <?php echo !empty($comment['ngay_tao']) ? date('d/m/Y H:i', strtotime($comment['ngay_tao'])) : ''; ?></div>
                                        </td>
                                        <td>
                                            <?php if (($comment['trang_thai'] ?? '') === 'hien'): ?>
                                                <span class="badge bg-success">Đang hiện</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Đã ẩn</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <ul class="d-flex gap-3 justify-content-center list-unstyled mb-0">
                                                <li>
                                                    <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=toggle_comment&id=<?php echo (int) $comment['ma_binh_luan']; ?>" class="text-secondary" title="<?php echo (($comment['trang_thai'] ?? '') === 'hien') ? 'Ẩn' : 'Hiện'; ?>">
                                                        <i class="fa-solid <?php echo (($comment['trang_thai'] ?? '') === 'hien') ? 'fa-eye-slash' : 'fa-eye'; ?>"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" class="text-danger" title="Xóa"
                                                       data-bs-toggle="modal" data-bs-target="#modalDeleteConfirm"
                                                       onclick="setDeleteUrl('<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=delete_comment&id=<?php echo (int) $comment['ma_binh_luan']; ?>')">
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

<div class="modal fade" id="modalDeleteConfirm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa bình luận</h5>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fa-solid fa-triangle-exclamation text-warning mb-3" style="font-size: 3rem;"></i>
                <p class="mb-0">Bạn có muốn xóa bình luận này không?</p>
                <p class="text-danger font-weight-bold mb-0">Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="#" id="btnConfirmDelete" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
</div>

<script>
    function setDeleteUrl(url) {
        var btn = document.getElementById('btnConfirmDelete');
        if (btn) {
            btn.href = url;
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/simple-datatables@10/dist/umd/simple-datatables.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var el = document.getElementById('dataTable');
        if (el) {
            new simpleDatatables.DataTable(el, {
                perPage: 10,
                searchable: true,
                sortable: true
            });
        }
    });
</script>