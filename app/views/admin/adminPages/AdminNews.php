<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/simple-datatables@10/dist/style.min.css">

<div class="row px-4">
    <div class="col-12 mt-5">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="header-title mb-0">Quản lý Tin tức và Bài viết</h4>
                    <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=news&admin_edit=create_news" class="btn btn-dark btn-sm">
                        <i class="ti-plus"></i> Thêm bài viết mới
                    </a>
                </div>

                <ul class="nav nav-tabs mb-4" id="categoryTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold <?php echo empty($_GET['cat_id']) ? 'active' : 'text-secondary'; ?>" 
                           href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=news">
                            Tất cả
                        </a>
                    </li>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <li class="nav-item">
                                <a class="nav-link font-weight-bold <?php echo (isset($_GET['cat_id']) && $_GET['cat_id'] == $cat['ma_loai']) ? 'active' : 'text-secondary'; ?>" 
                                   href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=news&cat_id=<?php echo $cat['ma_loai']; ?>">
                                    <?php echo htmlspecialchars($cat['ten_loai']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>

                <style>                 
                    /* Chỉnh sửa thông số của datable-dark */
                    /* Đổi màu mũi tên sort*/
                    .datatable-dark .datatable-sorter::before {
                        border-top-color: #ffffff !important;
                        opacity: 0.4;
                    }

                    .datatable-dark .datatable-sorter::after {
                        border-bottom-color: #ffffff !important;
                        opacity: 0.4;
                    }

                    /* Tăng opacity */
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
                                <th>Tiêu đề</th>
                                <th>Danh mục</th>
                                <th>Người đăng</th>
                                <th>Trạng thái</th>
                                <th>Ngày đăng</th>
                                <th>Ngày cập nhật</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($newsList)): ?>
                                <?php 
                                    $stt = 1; 
                                    foreach ($newsList as $news): 
                                ?>
                                    <tr>
                                        <td><?php echo $stt++; ?></td>
                                        
                                        <td class="text-left font-weight-bold" 
                                            style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                            title="<?php echo htmlspecialchars($news['tieu_de']); ?>">
                                            
                                            <?php echo htmlspecialchars($news['tieu_de']); ?>
                                            
                                        </td>
                                        
                                        <td class="text-left font-weight-bold">
                                            <?php echo htmlspecialchars($news['ten_loai']); ?>
                                        </td>
                                        
                                        <td><?php echo htmlspecialchars($news['ho_va_ten_dem'] . ' ' . $news['ten']); ?></td>
                                        
                                        <td>
                                            <?php if ($news['trang_thai'] == 'da_dang'): ?>
                                                <span class="text-left font-weight-bold">Đã đăng</span>
                                            <?php elseif ($news['trang_thai'] == 'luu_tru'): ?>
                                                <span class="text-left font-weight-bold">Lưu trữ</span>
                                            <?php else: ?>
                                                <span class="text-left font-weight-bold">Bản nháp</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-left" style="font-size: 0.85rem;">
                                            <div><i class="ti-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($news['ngay_dang'])); ?></div>
                                        </td>

                                        <td class="text-left" style="font-size: 0.85rem;">
                                            <div><i class="ti-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($news['ngay_cap_nhat'])); ?></div>
                                        </td>
                                        
                                        <td>
                                            <ul class="d-flex gap-3 justify-content-center">
                                                <li class="mr-2">
                                                    <a href="<?php echo BASE_URL; ?>/public/bai-viet/<?php echo urlencode($news['slug']); ?>" target="_blank" class="text-secondary" title="Xem giao diện thực tế">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                </li>
                                                
                                                <li class="mr-3">
                                                    <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=news_edit&id=<?php echo $news['ma_bai_viet']; ?>" class="text-secondary" title="Sửa bài viết">
                                                        <i class="fa-solid fa-edit"></i>
                                                    </a>
                                                </li>
                                                
                                                <li>
                                                    <a href="javascript:void(0);" class="text-danger" title="Xóa" 
                                                        data-bs-toggle="modal" data-bs-target="#modalDeleteConfirm" 
                                                        onclick="setDeleteUrl('<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=news_delete&id=<?php echo $news['ma_bai_viet']; ?>')">
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

<!-- Popup xác nhận xóa bài viết -->
<div class="modal fade" id="modalDeleteConfirm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa bài viết</h5>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fa-solid fa-triangle-exclamation text-warning mb-3" style="font-size: 3rem;"></i>
                <p class="mb-0">Bạn có muốn xóa bài viết này không?</p>
                <p class="text-danger font-weight-bold mb-0">Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="#" id="btnConfirmDelete" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
</div>

<!-- Hàm để nhận lệnh xóa -->
<script>
    function setDeleteUrl(url) {
        document.getElementById('btnConfirmDelete').href = url;
    }
</script>

<?php include __DIR__ . '/../adminComponents/AdminCreateNews.php'; ?>

<?php if ((isset($_GET['admin_edit']) && $_GET['admin_edit'] === 'create_news') || isset($newsToEdit)): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modalElement = document.getElementById('modalCreateNews');
        
        if (modalElement) {
            var myModal = new bootstrap.Modal(modalElement, {
                backdrop: 'static', // Chặn click ra ngoài viền đen
                keyboard: false     // Chặn nhấn nút ESC để tắt (tùy chọn)
            });
            
            myModal.show();
        } else {
            console.error('Không tìm thấy modalCreateNews!');
        }
    });
</script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/simple-datatables@10/dist/umd/simple-datatables.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tìm cái bảng có ID là dataTable
        var el = document.getElementById('dataTable');
        if (el) {
            new simpleDatatables.DataTable(el, { 
                perPage: 10,      // Số dòng trên 1 trang
                searchable: true, // Bật thanh tìm kiếm
                sortable: true    // Bật sắp xếp cột
            });
        }
    });
</script>