<?php
$adminAssetBase = (defined('BASE_URL') ? rtrim(BASE_URL, '/') : 'http://localhost/BookTab') . '/public/admin_assets';

// Get about data
$aboutController = new AboutController($dbConnection);
$aboutPage = $aboutController->getAboutForEdit();

$successMsg = $_SESSION['admin_about_success'] ?? '';
$errorMsg = $_SESSION['admin_about_error'] ?? '';
unset($_SESSION['admin_about_success'], $_SESSION['admin_about_error']);
?>

<div class="main-content-inner">
    <div class="row">
        <div class="col-12">
            <div class="card mt-4">
                <div class="card-body">
                    <h4 class="header-title mb-4">Chỉnh sửa trang Giới thiệu</h4>

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

                    <?php if (!$aboutPage): ?>
                        <div class="alert alert-warning">Không tìm thấy nội dung Giới thiệu trong cơ sở dữ liệu.</div>
                    <?php else: ?>
                        <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=about">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="ma_thong_tin" value="<?php echo (int)$aboutPage['ma_thong_tin']; ?>">

                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Nội dung trang Giới thiệu</label>
                                <textarea id="noi_dung" name="noi_dung"><?php echo htmlspecialchars($aboutPage['noi_dung'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="ti-save"></i> Lưu thay đổi
                            </button>
                            <a href="<?php echo BASE_URL; ?>/public/index.php?page=about" target="_blank" class="btn btn-outline-secondary btn-lg ms-2">
                                <i class="ti-eye"></i> Xem trang
                            </a>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo $adminAssetBase; ?>/tinymce/tinymce.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    tinymce.init({
        selector: '#noi_dung',
        height: 600,
        menubar: 'file edit view insert format table tools',
        plugins: 'advlist lists link image table code fullscreen preview searchreplace wordcount autolink emoticons media codesample charmap insertdatetime template visualblocks anchor accordion nonbreaking',
        toolbar: 'undo redo | blocks | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | table accordion | charmap emoticons codesample insertdatetime nonbreaking | template visualblocks | searchreplace code fullscreen preview',
        content_style: 'body { font-family: "Be Vietnam Pro", sans-serif; font-size: 16px; line-height: 1.75; color: #374151; } h1 { font-size: 2em; font-weight: 800; line-height: 1.25; margin-top: 0; margin-bottom: 0.5em; color: #111827; } h2 { font-size: 1.5em; font-weight: 700; line-height: 1.333; margin-top: 1.5em; margin-bottom: 0.75em; padding-bottom: 0.3em; border-bottom: 1px solid #e5e7eb; color: #111827; } h3 { font-size: 1.25em; font-weight: 600; line-height: 1.6; margin-top: 1.25em; margin-bottom: 0.5em; color: #111827; } p { margin-top: 0.75em; margin-bottom: 0.75em; } ul, ol { margin-top: 0.75em; margin-bottom: 0.75em; padding-left: 1.5em; } ul { list-style-type: disc; } ol { list-style-type: decimal; } li { margin-top: 0.25em; margin-bottom: 0.25em; } blockquote { margin-top: 1em; margin-bottom: 1em; padding-left: 1em; border-left: 4px solid #ef4444; color: #6b7280; font-style: italic; } a { color: #ef4444; text-decoration: underline; } strong { font-weight: 600; } img { max-width: 100%; height: auto; } table { border-collapse: collapse; width: 100%; } table th, table td { border: 1px solid #d1d5db; padding: 0.5em 0.75em; }',
        branding: false,
        promotion: false,
        language: 'vi',
        automatic_uploads: false,
        file_picker_types: 'image',
        images_upload_handler: function(blobInfo, progress) {
            return new Promise(function(resolve) {
                var reader = new FileReader();
                reader.onload = function() {
                    resolve(reader.result);
                };
                reader.readAsDataURL(blobInfo.blob());
            });
        }
    });
});
</script>
