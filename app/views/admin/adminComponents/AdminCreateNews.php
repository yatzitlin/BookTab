<?php
    // Kiểm tra xem biến $newsToEdit có tồn tại và có dữ liệu không (Chế độ Sửa)
    $isEdit = isset($newsToEdit) && !empty($newsToEdit);
    
    // Tự động đổi đường dẫn Action của form
    $formAction = $isEdit 
        ? BASE_URL . '/public/index.php?page=admin&admin_action=news_update&id=' . $newsToEdit['ma_bai_viet']
        : BASE_URL . '/public/index.php?page=admin&admin_action=news_store';
?>

<div class="modal fade" id="modalCreateNews" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center w-100">
                <h5 class="modal-title mb-0">
                    <?php echo $isEdit ? 'Chỉnh Sửa Bài Viết' : 'Thêm Bài Viết Mới'; ?>
                </h5>
                <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=news" class="close ms-auto">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>

            <div class="modal-body">
                <form action="<?php echo $formAction; ?>" method="POST" enctype="multipart/form-data">
                    
                    <div class="form-group">
                        <label class="col-form-label">Tiêu đề bài viết <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="tieu_de" required placeholder="Nhập tiêu đề..."
                               value="<?php echo $isEdit ? htmlspecialchars($newsToEdit['tieu_de']) : ''; ?>">
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 form-group">
                            <label class="col-form-label">Danh mục <span class="text-danger">*</span></label>
                            
                            <select class="custom-select" name="ma_loai" id="select_ma_loai" required onchange="handleCategoryChange()">
                                <option value="" disabled <?php echo !$isEdit ? 'selected' : ''; ?>>-- Chọn danh mục --</option>
                                
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['ma_loai']; ?>"
                                            <?php echo ($isEdit && $newsToEdit['ma_loai'] == $cat['ma_loai']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['ten_loai']); ?>
                                    </option>
                                <?php endforeach; ?>
                                
                                <option value="new" class="font-weight-bold text-primary">+ Thêm danh mục mới</option>
                            </select>
                            
                            <input type="text" 
                                class="form-control mt-2 border-primary" 
                                name="ten_loai_moi" 
                                id="input_ten_loai_moi" 
                                placeholder="Nhập tên danh mục mới..." 
                                style="display: none;"
                            >
                        </div>

                        <div class="col-md-6 form-group">
                            <label class="col-form-label">Trạng thái bài viết <span class="text-danger">*</span></label>
                            <select class="custom-select" name="trang_thai" required>
                                <option value="ban_nhap" <?php echo ($isEdit && $newsToEdit['trang_thai'] == 'ban_nhap') ? 'selected' : ''; ?>>Bản nháp</option>
                                <option value="da_dang" <?php echo ($isEdit && $newsToEdit['trang_thai'] == 'da_dang') ? 'selected' : ''; ?>>Đã đăng</option>
                                <option value="luu_tru" <?php echo ($isEdit && $newsToEdit['trang_thai'] == 'luu_tru') ? 'selected' : ''; ?>>Lưu trữ</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">
                            Ảnh đại diện <?php echo $isEdit ? '(Để trống nếu giữ nguyên ảnh cũ)' : ''; ?>
                        </label>
                        
                        <?php if($isEdit && !empty($newsToEdit['thumbnail_url'])): ?>
                            <div class="mb-2">
                                <img src="<?php echo BASE_URL . '/public/' . htmlspecialchars($newsToEdit['thumbnail_url']); ?>" alt="Thumbnail hiện tại" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        <?php endif; ?>
                        
                        <input type="file" class="form-control" name="thumbnail" accept="image/*">
                    </div>
                    
                    <div class="form-group">
                        <label class="col-form-label">Tóm tắt bài viết <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="tom_tat" rows="3" required placeholder="Nhập một đoạn tóm tắt ngắn gọn..."><?php echo $isEdit ? htmlspecialchars($newsToEdit['tom_tat']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-form-label">Nội dung <span class="text-danger">*</span></label>
                        <textarea name="noi_dung" id="editorThemBaiViet"><?php echo $isEdit ? htmlspecialchars($newsToEdit['noi_dung']) : ''; ?></textarea>
                    </div>

                    <div class="text-right mt-3">
                        <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=news" class="btn btn-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> <?php echo $isEdit ? 'Cập nhật' : 'Lưu bài viết'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function handleCategoryChange() {
        var selectElement = document.getElementById('select_ma_loai');
        var inputElement = document.getElementById('input_ten_loai_moi');
        
        if (selectElement.value === 'new') {
            inputElement.style.display = 'block';
            inputElement.setAttribute('required', 'required');
            inputElement.focus();
        } else {
            inputElement.style.display = 'none';
            inputElement.removeAttribute('required');
            inputElement.value = '';
        }
    }
</script>

<!-- Gõ nội dung với công cụ như Word -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>

<script>
    // Fix lỗi không gõ được chữ vào các popup phụ của TinyMCE khi dùng trong Bootstrap Modal
    // Nếu không có hàm này thì xảy ra tình trạng option của thanh toolbar bị lệch vị trí
    document.addEventListener('focusin', function (e) { 
        if (e.target.closest('.tox-tinymce-aux, .moxman-window, .tam-assetmanager-root') !== null) { 
            e.stopImmediatePropagation();
        } 
    });

    tinymce.init({
        selector: '#editorThemBaiViet', 
        ui_container: '#modalCreateNews',
        
        // Chiều cao khung soạn thảo
        height: 500,
        
        // Bật plugins
        plugins: 'advlist autolink lists link image charmap preview anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking table emoticons template help',
        
        // Toolbar
        toolbar: 'undo redo | styles | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | forecolor backcolor emoticons | preview fullscreen',
        
        // Chú thích hình ảnh
        image_caption: true,

        // Tắt phần quảng cáo của thư viện TinyMCE
        promotion: false, // Tắt nút "Upgrade"
        branding: false,  // Tắt chữ "Powered by TinyMCE"

        // Cấu hình upload ảnh
        images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            // Đường dẫn gọi đến PHP
            xhr.open('POST', '<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=news_upload_image');

            xhr.onload = () => {
                if (xhr.status === 403) { reject({ message: 'Lỗi phân quyền HTTP 403', remove: true }); return; }
                if (xhr.status < 200 || xhr.status >= 300) { reject('Lỗi HTTP: ' + xhr.status); return; }
                
                // Ép kiểu JSON từ PHP trả về
                const json = JSON.parse(xhr.responseText);
                if (!json || typeof json.location != 'string') {
                    reject('JSON không hợp lệ: ' + xhr.responseText);
                    return;
                }
                // Trả link ảnh cho TinyMCE hiển thị
                resolve(json.location);
            };

            xhr.onerror = () => { reject('Lỗi đường truyền XHR!'); };

            const formData = new FormData();
            // TinyMCE gói ảnh vào biến có tên là 'file'
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            // Lấy dữ liệu từ ô Nhập tiêu đề gửi kèm lên Server
            const inputTieuDe = document.querySelector('input[name="tieu_de"]').value;
            formData.append('tieu_de_ajax', inputTieuDe);
            
            xhr.send(formData);
        }),
        
        setup: function (editor) {
            editor.on('change', function () {
                editor.save(); 
            });
        }
    });
</script>