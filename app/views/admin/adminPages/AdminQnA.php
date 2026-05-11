<?php
$adminAssetBase = (defined('BASE_URL') ? rtrim(BASE_URL, '/') : 'http://localhost/BookTab') . '/public/admin_assets';
$qnaController = new QnAController($dbConnection);

$act = isset($_GET['act']) ? trim($_GET['act']) : 'questions';
$selectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$currentPage = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$perPage = 15;

$categories = $qnaController->getCategories();

// ---- Breadcrumb động theo tab ----
$qnaBaseUrl = BASE_URL . '/public/index.php?page=admin&admin_action=qna';
$_bcQna = ['label' => 'Hỏi đáp', 'url' => $qnaBaseUrl . '&act=questions'];
switch ($act) {
    case 'faq':
        $adminPageBreadcrumbOverride = [$_bcQna, ['label' => 'FAQ']];
        break;
    case 'categories':
        $adminPageBreadcrumbOverride = [$_bcQna, ['label' => 'Quản lý chủ đề']];
        break;
    case 'view':
        // sẽ được xác định lại sau khi biết is_faq của câu hỏi
        $adminPageBreadcrumbOverride = null; // placeholder, set bên dưới
        break;
    default: // questions
        $adminPageBreadcrumbOverride = [$_bcQna, ['label' => 'Danh sách câu hỏi']];
        break;
}

$successMsg = $_SESSION['admin_qna_success'] ?? '';
$errorMsg = $_SESSION['admin_qna_error'] ?? '';
unset($_SESSION['admin_qna_success'], $_SESSION['admin_qna_error']);

function statusBadge($trangThai) {
    switch ($trangThai) {
        case 'cho_duyet':   return '<span class="badge bg-warning text-dark"><i class="ti-time"></i> Chờ duyệt</span>';
        case 'chua_tra_loi': return '<span class="badge bg-info"><i class="ti-comment"></i> Chưa trả lời</span>';
        case 'da_tra_loi':   return '<span class="badge bg-success"><i class="ti-check"></i> Đã trả lời</span>';
        case 'da_an':        return '<span class="badge bg-secondary"><i class="ti-eye-slash"></i> Đã ẩn</span>';
        default:             return '<span class="badge bg-light text-dark">' . htmlspecialchars($trangThai) . '</span>';
    }
}

function statusRowClass($trangThai) {
    switch ($trangThai) {
        case 'cho_duyet':   return 'table-warning';
        case 'da_an':       return 'table-secondary';
        default:            return '';
    }
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox@3.3.0/dist/css/glightbox.min.css">
<style>
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.table tbody tr {
    animation: fadeInUp 0.3s ease forwards;
    opacity: 0;
    transition: all 0.2s ease;
}
.table tbody tr:nth-child(1) { animation-delay: 0.02s; }
.table tbody tr:nth-child(2) { animation-delay: 0.04s; }
.table tbody tr:nth-child(3) { animation-delay: 0.06s; }
.table tbody tr:nth-child(4) { animation-delay: 0.08s; }
.table tbody tr:nth-child(5) { animation-delay: 0.10s; }
.table tbody tr:nth-child(6) { animation-delay: 0.12s; }
.table tbody tr:nth-child(7) { animation-delay: 0.14s; }
.table tbody tr:nth-child(8) { animation-delay: 0.16s; }
.table tbody tr:nth-child(9) { animation-delay: 0.18s; }
.table tbody tr:nth-child(10) { animation-delay: 0.20s; }
.table tbody tr:nth-child(11) { animation-delay: 0.22s; }
.table tbody tr:nth-child(12) { animation-delay: 0.24s; }
.table tbody tr:nth-child(13) { animation-delay: 0.26s; }
.table tbody tr:nth-child(14) { animation-delay: 0.28s; }
.table tbody tr:nth-child(15) { animation-delay: 0.30s; }
.table tbody tr:hover {
    transform: translateX(4px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.card {
    transition: box-shadow 0.3s ease;
}
.card:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
.btn {
    transition: all 0.2s ease;
}
.btn:active {
    transform: scale(0.97);
}
.badge.bg-info {
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}
.img-thumb {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 8px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    cursor: pointer;
}
.img-thumb:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.img-gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.img-counter {
    position: relative;
}
.img-counter .badge {
    position: absolute;
    top: -4px;
    right: -4px;
    font-size: 0.6rem;
    padding: 2px 5px;
    border-radius: 50%;
}
.category-btn {
    transition: all 0.2s ease;
}
.category-btn:hover {
    transform: translateY(-1px);
}
</style>

<div class="main-content-inner">
    <?php if (!empty($successMsg)): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <?php echo htmlspecialchars($successMsg, ENT_QUOTES, 'UTF-8'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (!empty($errorMsg)): ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <?php echo htmlspecialchars($errorMsg, ENT_QUOTES, 'UTF-8'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($act === 'view' && isset($_GET['id'])): ?>
        <!-- ==================== DETAIL VIEW ==================== -->
        <?php
        $questionId = (int)$_GET['id'];
        $detail = $qnaController->adminGetQuestionDetail($questionId);
        $isFaqQuestion = !empty($detail) && ($detail['is_faq'] ?? 'No') === 'Yes';
        $backTab = $isFaqQuestion ? 'faq' : 'questions';
        // Set breadcrumb chính xác dựa vào loại câu hỏi
        if ($isFaqQuestion) {
            $adminPageBreadcrumbOverride = [
                $_bcQna,
                ['label' => 'FAQ', 'url' => $qnaBaseUrl . '&act=faq'],
                ['label' => 'Chi tiết câu hỏi'],
            ];
        } else {
            $adminPageBreadcrumbOverride = [
                $_bcQna,
                ['label' => 'Danh sách câu hỏi', 'url' => $qnaBaseUrl . '&act=questions'],
                ['label' => 'Chi tiết câu hỏi'],
            ];
        }
        if (!$detail):
        ?>
            <div class="alert alert-warning mt-3">Không tìm thấy câu hỏi.</div>
        <?php else: ?>
            <div class="card mt-3">
                <div class="card-body">
                    <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna&act=<?php echo $backTab; ?><?php echo $selectedCategory > 0 ? '&category=' . $selectedCategory : ''; ?>" class="btn btn-outline-secondary btn-sm mb-3">
                        <i class="ti-arrow-left"></i> <?php echo $isFaqQuestion ? 'Quay lại danh sách FAQ' : 'Quay lại danh sách'; ?>
                    </a>

                    <h4 class="header-title mb-3"><i class="ti-help-alt"></i> Chi tiết câu hỏi</h4>

                    <div class="row mb-4 p-3 bg-light rounded" style="border-left: 4px solid #17a2b8;">
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr><th style="width:140px">Câu hỏi:</th><td><strong><?php echo htmlspecialchars($detail['ten_cau_hoi'], ENT_QUOTES, 'UTF-8'); ?></strong></td></tr>
                                <tr><th>Chủ đề:</th><td><span class="badge bg-info"><?php echo htmlspecialchars($detail['ten_loai'], ENT_QUOTES, 'UTF-8'); ?></span></td></tr>
                                <tr><th>Người hỏi:</th><td><?php echo htmlspecialchars(trim($detail['user_ho_ten_dem'] . ' ' . $detail['user_ten']), ENT_QUOTES, 'UTF-8'); ?></td></tr>
                                <tr><th>Ngày tạo:</th><td><?php echo htmlspecialchars($detail['ngay_tao'], ENT_QUOTES, 'UTF-8'); ?></td></tr>
                                <tr><th>Trạng thái:</th><td><?php echo statusBadge($detail['trang_thai']); ?></td></tr>
                                <tr><th>Loại:</th><td><?php echo $detail['is_faq'] === 'Yes' ? '<span class="badge bg-warning text-dark"><i class="ti-star"></i> FAQ</span>' : '<span class="badge bg-secondary">Câu hỏi thường</span>'; ?></td></tr>
                            </table>
                        </div>
                        <div class="col-md-4 text-end">
                            <?php if ($detail['trang_thai'] === 'da_an'): ?>
                                <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna" class="d-inline mb-2">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="hidden" name="act" value="unhide">
                                    <input type="hidden" name="id" value="<?php echo $questionId; ?>">
                                    <button type="submit" class="btn btn-outline-warning btn-sm"><i class="ti-eye"></i> Hiện</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna" class="d-inline mb-2">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="hidden" name="act" value="hide">
                                    <input type="hidden" name="id" value="<?php echo $questionId; ?>">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm"><i class="ti-eye-slash"></i> Ẩn</button>
                                </form>
                            <?php endif; ?>

                            <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna" class="d-inline mb-2"
                                  onsubmit="return confirm('Xoá câu hỏi này? Thao tác không thể hoàn tác!');">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="act" value="delete">
                                <input type="hidden" name="id" value="<?php echo $questionId; ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm"><i class="ti-trash"></i> Xoá</button>
                            </form>
                        </div>
                    </div>

                    <?php if (!empty($detail['images'])): ?>
                        <div class="mb-4">
                            <h6><i class="ti-image"></i> Ảnh đính kèm câu hỏi <span class="badge bg-light text-dark"><?php echo count($detail['images']); ?></span></h6>
                            <div class="img-gallery">
                                <?php foreach ($detail['images'] as $img): ?>
                                    <?php $imgUrl = BASE_URL . '/' . htmlspecialchars(ltrim($img['url_anh'], '/'), ENT_QUOTES, 'UTF-8'); ?>
                                    <a href="<?php echo $imgUrl; ?>" class="glightbox" data-gallery="qna-images">
                                        <img src="<?php echo $imgUrl; ?>" loading="lazy" class="img-thumb" alt="">
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <hr>

                    <?php if (!empty($detail['ma_cau_tra_loi'])): ?>
                        <h5 class="mb-3"><i class="ti-comment-alt"></i> Câu trả lời</h5>
                        <div class="card bg-light mb-3" style="border-left: 4px solid #28a745;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">
                                        Trả lời bởi: <strong><?php echo htmlspecialchars(trim($detail['admin_ho_ten_dem'] . ' ' . $detail['admin_ten']), ENT_QUOTES, 'UTF-8'); ?></strong>
                                        - <?php echo htmlspecialchars($detail['ngay_tra_loi'], ENT_QUOTES, 'UTF-8'); ?>
                                    </small>
                                    <div>
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('editAnswerForm').style.display='block'; this.style.display='none';">
                                            <i class="ti-pencil"></i> Sửa
                                        </button>
                                        <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna" class="d-inline"
                                              onsubmit="return confirm('Xoá câu trả lời này?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" name="act" value="delete_answer">
                                            <input type="hidden" name="id" value="<?php echo (int)$detail['ma_cau_tra_loi']; ?>">
                                            <input type="hidden" name="question_id" value="<?php echo $questionId; ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="ti-trash"></i> Xoá</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="mb-3"><?php echo $detail['cau_tra_loi']; ?></div>

                                <?php if (!empty($detail['answer_images'])): ?>
                                    <div class="img-gallery mb-3">
                                        <?php foreach ($detail['answer_images'] as $img): ?>
                                            <?php $aImgUrl = BASE_URL . '/' . htmlspecialchars(ltrim($img['url_anh'], '/'), ENT_QUOTES, 'UTF-8'); ?>
                                            <a href="<?php echo $aImgUrl; ?>" class="glightbox" data-gallery="answer-images">
                                                <img src="<?php echo $aImgUrl; ?>" loading="lazy" class="img-thumb" style="width:100px;height:100px;" alt="">
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div id="editAnswerForm" style="display:none;">
                                    <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="act" value="edit_answer">
                                        <input type="hidden" name="id" value="<?php echo (int)$detail['ma_cau_tra_loi']; ?>">
                                        <input type="hidden" name="question_id" value="<?php echo $questionId; ?>">
                                        <div class="form-group mb-3">
                                            <textarea name="noi_dung" class="form-control" rows="5" required><?php echo htmlspecialchars($detail['cau_tra_loi'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm"><i class="ti-save"></i> Cập nhật</button>
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('editAnswerForm').style.display='none';">Huỷ</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <h5 class="mb-3">Trả lời câu hỏi</h5>
                        <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="act" value="answer">
                            <input type="hidden" name="id" value="<?php echo $questionId; ?>">
                            <div class="form-group mb-3">
                                <textarea name="noi_dung" class="form-control" rows="5" placeholder="Nhập nội dung trả lời..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="ti-check"></i> Gửi trả lời</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

    <?php elseif ($act === 'faq'): ?>
        <!-- ==================== FAQ VIEW ==================== -->
        <?php
        $faqData = $qnaController->adminGetFaqItems($currentPage, $perPage, $selectedCategory);
        $catParam = $selectedCategory > 0 ? '&category=' . $selectedCategory : '';
        ?>
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="header-title mb-0">Quản lý FAQ</h4>
                    <div>
                        <button type="button" class="btn btn-success btn-sm me-1" data-bs-toggle="collapse" data-bs-target="#createFaqForm">
                            <i class="ti-plus"></i> Tạo FAQ
                        </button>
                        <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna&act=questions" class="btn btn-outline-secondary btn-sm">
                            <i class="ti-arrow-left"></i> Câu hỏi
                        </a>
                    </div>
                </div>

                <div class="collapse mb-4" id="createFaqForm">
                    <div class="card bg-light">
                        <div class="card-body">
                            <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="act" value="create_faq">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Câu hỏi</label>
                                        <input type="text" name="ten_cau_hoi" class="form-control" placeholder="Nhập câu hỏi FAQ..." required minlength="10" maxlength="255">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Chủ đề</label>
                                        <select name="ma_loai" class="form-select" required>
                                            <option value="">-- Chọn chủ đề --</option>
                                            <?php foreach ($categories as $cat): ?>
                                                <option value="<?php echo (int)$cat['ma_loai']; ?>"><?php echo htmlspecialchars($cat['ten_loai'], ENT_QUOTES, 'UTF-8'); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100"><i class="ti-save"></i> Tạo</button>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Câu trả lời</label>
                                        <textarea name="noi_dung" class="form-control" rows="4" placeholder="Nhập nội dung trả lời..." required></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna&act=faq"
                       class="btn btn-sm category-btn <?php echo $selectedCategory === 0 ? 'btn-primary' : 'btn-outline-primary'; ?> me-1">Tất cả</a>
                    <?php foreach ($categories as $cat): ?>
                        <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna&act=faq&category=<?php echo (int)$cat['ma_loai']; ?>"
                           class="btn btn-sm category-btn <?php echo $selectedCategory === (int)$cat['ma_loai'] ? 'btn-primary' : 'btn-outline-primary'; ?> me-1">
                            <?php echo htmlspecialchars($cat['ten_loai'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Câu hỏi</th>
                                <th>Chủ đề</th>
                                <th>Người tạo (Admin)</th>
                                <th>Người trả lời</th>
                                <th>Trạng thái</th>
                                <th>Ngày</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($faqData['items'])): ?>
                                <tr><td colspan="8" class="text-center text-muted">Chưa có FAQ nào.</td></tr>
                            <?php else: ?>
                                <?php foreach ($faqData['items'] as $i => $item): ?>
                                    <tr>
                                        <td><?php echo ($currentPage - 1) * $perPage + $i + 1; ?></td>
                                        <td><?php echo htmlspecialchars($item['ten_cau_hoi'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><span class="badge bg-info"><?php echo htmlspecialchars($item['ten_loai'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                        <td><?php echo htmlspecialchars(trim($item['user_ho_ten_dem'] . ' ' . $item['user_ten']), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo !empty($item['admin_ho_ten_dem']) ? htmlspecialchars(trim($item['admin_ho_ten_dem'] . ' ' . $item['admin_ten']), ENT_QUOTES, 'UTF-8') : '<span class="text-muted">-</span>'; ?></td>
                                        <td><?php echo statusBadge($item['trang_thai']); ?></td>
                                        <td><?php echo htmlspecialchars($item['ngay_tao'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna&act=view&id=<?php echo (int)$item['ma_cau_hoi']; ?>"
                                               class="btn btn-sm btn-outline-primary"><i class="ti-eye"></i></a>
                                            <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna" class="d-inline"
                                                  onsubmit="return confirm('Xoá câu hỏi này?');">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" name="act" value="delete">
                                                <input type="hidden" name="id" value="<?php echo (int)$item['ma_cau_hoi']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="ti-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($faqData['totalPages'] > 1): ?>
                    <nav class="mt-3">
                        <ul class="pagination justify-content-center">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item"><a class="page-link" href="?page=admin&admin_action=qna&act=faq&p=<?php echo $currentPage - 1 . $catParam; ?>">Trước</a></li>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $faqData['totalPages']; $i++): ?>
                                <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=admin&admin_action=qna&act=faq&p=<?php echo $i . $catParam; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <?php if ($currentPage < $faqData['totalPages']): ?>
                                <li class="page-item"><a class="page-link" href="?page=admin&admin_action=qna&act=faq&p=<?php echo $currentPage + 1 . $catParam; ?>">Sau</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>

    <?php elseif ($act === 'categories'): ?>
        <!-- ==================== CATEGORIES VIEW ==================== -->
        <?php
        $editCat = null;
        if (isset($_GET['edit_cat'])) {
            $editId = (int)$_GET['edit_cat'];
            foreach ($categories as $c) {
                if ((int)$c['ma_loai'] === $editId) { $editCat = $c; break; }
            }
        }
        ?>
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="header-title mb-0">Quản lý Chủ đề</h4>
                    <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna&act=questions" class="btn btn-outline-secondary btn-sm">
                        <i class="ti-arrow-left"></i> Câu hỏi
                    </a>
                </div>

                <div class="card bg-light mb-4">
                    <div class="card-body">
                        <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            <?php if ($editCat): ?>
                                <input type="hidden" name="act" value="edit_category">
                                <input type="hidden" name="id" value="<?php echo (int)$editCat['ma_loai']; ?>">
                            <?php else: ?>
                                <input type="hidden" name="act" value="add_category">
                            <?php endif; ?>
                            <div class="row g-2">
                                <div class="col-md-8">
                                    <input type="text" name="ten_loai" class="form-control"
                                           placeholder="Tên chủ đề..."
                                           value="<?php echo $editCat ? htmlspecialchars($editCat['ten_loai'], ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary"><?php echo $editCat ? 'Cập nhật' : 'Thêm mới'; ?></button>
                                    <?php if ($editCat): ?>
                                        <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna&act=categories" class="btn btn-secondary">Huỷ</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna" id="reorder-form">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="act" value="reorder_categories">
                    <input type="hidden" name="order" id="reorder-input" value="">

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">Kéo thả hàng để thay đổi thứ tự, sau đó bấm "Lưu thứ tự".</small>
                        <button type="submit" class="btn btn-success btn-sm"><i class="ti-save"></i> Lưu thứ tự</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead><tr><th style="width:50px"></th><th style="width:60px">STT</th><th>Tên chủ đề</th><th>Hành động</th></tr></thead>
                            <tbody id="category-list">
                                <?php foreach ($categories as $i => $cat): ?>
                                    <tr data-id="<?php echo (int)$cat['ma_loai']; ?>" style="cursor:grab;">
                                        <td class="text-center align-middle"><i class="ti-menu" style="opacity:0.4;"></i></td>
                                        <td class="stt-cell align-middle"><?php echo $i + 1; ?></td>
                                        <td class="align-middle"><?php echo htmlspecialchars($cat['ten_loai'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna&act=categories&edit_cat=<?php echo (int)$cat['ma_loai']; ?>"
                                               class="btn btn-sm btn-outline-primary"><i class="ti-pencil"></i></a>
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-cat-btn" data-id="<?php echo (int)$cat['ma_loai']; ?>" title="Xoá"><i class="ti-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </form>

                <?php foreach ($categories as $cat): ?>
                    <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna" class="d-none delete-cat-form" id="delete-cat-<?php echo (int)$cat['ma_loai']; ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="act" value="delete_category">
                        <input type="hidden" name="id" value="<?php echo (int)$cat['ma_loai']; ?>">
                    </form>
                <?php endforeach; ?>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
        <script>
        (function() {
            var el = document.getElementById('category-list');
            if (!el) return;
            new Sortable(el, {
                handle: '.ti-menu',
                animation: 150,
                ghostClass: 'table-active',
                onEnd: function() {
                    var rows = el.querySelectorAll('tr[data-id]');
                    var order = [];
                    rows.forEach(function(row, i) {
                        row.querySelector('.stt-cell').textContent = i + 1;
                        order.push(parseInt(row.dataset.id));
                    });
                    document.getElementById('reorder-input').value = JSON.stringify(order);
                }
            });

            document.querySelectorAll('.delete-cat-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var id = this.dataset.id;
                    if (confirm('Xoá chủ đề này?')) {
                        document.getElementById('delete-cat-' + id).submit();
                    }
                });
            });
        })();
        </script>

    <?php else: ?>
        <!-- ==================== QUESTIONS LIST (default) ==================== -->
        <?php
        $qnaData = $qnaController->adminGetQuestions($currentPage, $perPage, $selectedCategory);
        $catParam = $selectedCategory > 0 ? '&category=' . $selectedCategory : '';
        ?>
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="header-title mb-0">Câu hỏi của người dùng</h4>
                    <div>
                        <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna&act=faq" class="btn btn-outline-info btn-sm me-1">
                            <i class="ti-layout-list-post"></i> FAQ
                        </a>
                        <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna&act=categories" class="btn btn-outline-secondary btn-sm">
                            <i class="ti-tag"></i> Chủ đề
                        </a>
                    </div>
                </div>

                <div class="mb-3">
                    <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna&act=questions"
                       class="btn btn-sm category-btn <?php echo $selectedCategory === 0 ? 'btn-primary' : 'btn-outline-primary'; ?> me-1">Tất cả</a>
                    <?php foreach ($categories as $cat): ?>
                        <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna&act=questions&category=<?php echo (int)$cat['ma_loai']; ?>"
                           class="btn btn-sm category-btn <?php echo $selectedCategory === (int)$cat['ma_loai'] ? 'btn-primary' : 'btn-outline-primary'; ?> me-1">
                            <?php echo htmlspecialchars($cat['ten_loai'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Câu hỏi</th>
                                <th>Chủ đề</th>
                                <th>Người hỏi</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($qnaData['items'])): ?>
                                <tr><td colspan="7" class="text-center text-muted">Chưa có câu hỏi nào.</td></tr>
                            <?php else: ?>
                                <?php foreach ($qnaData['items'] as $i => $item): ?>
                                    <tr class="<?php echo statusRowClass($item['trang_thai']); ?>">
                                        <td><?php echo ($currentPage - 1) * $perPage + $i + 1; ?></td>
                                        <td><?php echo htmlspecialchars($item['ten_cau_hoi'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><span class="badge bg-info"><?php echo htmlspecialchars($item['ten_loai'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                        <td><?php echo htmlspecialchars(trim($item['user_ho_ten_dem'] . ' ' . $item['user_ten']), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo statusBadge($item['trang_thai']); ?></td>
                                        <td><?php echo htmlspecialchars($item['ngay_tao'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna&act=view&id=<?php echo (int)$item['ma_cau_hoi']; ?><?php echo $catParam; ?>"
                                               class="btn btn-sm btn-outline-primary" title="Chỉnh sửa"><i class="ti-pencil"></i></a>
                                            <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna" class="d-inline"
                                                  onsubmit="return confirm('Xoá câu hỏi này?');">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" name="act" value="delete">
                                                <input type="hidden" name="id" value="<?php echo (int)$item['ma_cau_hoi']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Xoá"><i class="ti-trash"></i></button>
                                            </form>
                                            <?php if ($item['trang_thai'] === 'cho_duyet'): ?>
                                                <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna" class="d-inline">
                                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                                    <input type="hidden" name="act" value="approve">
                                                    <input type="hidden" name="id" value="<?php echo (int)$item['ma_cau_hoi']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-success" title="Duyệt"><i class="ti-check"></i></button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($qnaData['totalPages'] > 1): ?>
                    <nav class="mt-3">
                        <ul class="pagination justify-content-center">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item"><a class="page-link" href="?page=admin&admin_action=qna&act=questions&p=<?php echo $currentPage - 1 . $catParam; ?>">Trước</a></li>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $qnaData['totalPages']; $i++): ?>
                                <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=admin&admin_action=qna&act=questions&p=<?php echo $i . $catParam; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <?php if ($currentPage < $qnaData['totalPages']): ?>
                                <li class="page-item"><a class="page-link" href="?page=admin&admin_action=qna&act=questions&p=<?php echo $currentPage + 1 . $catParam; ?>">Sau</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/glightbox@3.3.0/dist/js/glightbox.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    GLightbox({ selector: '.glightbox', touchNavigation: true, loop: true });
    document.querySelectorAll('[title]').forEach(function(el) {
        if (el.closest('.table')) {
            new bootstrap.Tooltip(el, { trigger: 'hover', placement: 'top' });
        }
    });
});
</script>
