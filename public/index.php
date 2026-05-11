<?php
define('BASE_URL', 'http://localhost/BookTab');

require_once __DIR__ . '/../app/controllers/AuthController.php';
// Enable display errors for development troubleshooting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/core/Database.php'; 
require_once __DIR__ . '/../app/controllers/AboutController.php';
require_once __DIR__ . '/../app/controllers/QnAController.php';
require_once '../app/controllers/NewsController.php';

$database = new Database();
$dbConnection = $database->connect();
$GLOBALS['dbConnection'] = $dbConnection;

// Cấu hình cài đặt cookie session bảo mật
AuthController::configureSessionSecurity();

session_start();

// Tạo một thực thể AuthController duy nhất để xử lý session và yêu cầu.
$authController = new AuthController($dbConnection);

// Kiểm tra session timeout trước khi xử lý
$authController->checkSessionTimeout();

// Tạo token chống CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Kiểm tra action
$action = isset($_GET['action']) ? $_GET['action'] : null;

if ($action === 'register') {
    $authController->handleRegister();
    exit;
} elseif ($action === 'login') {
    $authController->handleLogin();
    exit;
} elseif ($action === 'logout') {
    $authController->logout();
    exit;
}

// Nếu không có action, kiểm tra page
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Kiểm tra quyền admin
if ($page === 'admin' || $page === 'admin_dashboard') {
    if (!isset($_SESSION['userid']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrator') {
        header('Location: ' . BASE_URL . '/public/index.php?page=login&error=unauthorized');
        exit;
    }
}

// Admin POST handlers
if ($page === 'admin' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        $_SESSION['admin_qna_error'] = 'CSRF token không hợp lệ.';
        header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=' . ($_GET['admin_action'] ?? 'dashboard'));
        exit;
    }

    $adminAction = $_GET['admin_action'] ?? '';
    $adminUserId = (int)$_SESSION['userid'];

    if ($adminAction === 'about') {
        require_once __DIR__ . '/../app/controllers/AboutController.php';
        $aboutCtrl = new AboutController($dbConnection);
        $result = $aboutCtrl->updateAboutPage($_POST['noi_dung'] ?? '');
        if (isset($result['error'])) {
            $_SESSION['admin_about_error'] = $result['error'];
        } else {
            $_SESSION['admin_about_success'] = 'Đã cập nhật trang Giới thiệu thành công.';
        }
        header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=about');
        exit;
    }

    if ($adminAction === 'info') {
        require_once __DIR__ . '/../app/models/ThongTinModel.php';
        $thongTinModel = new ThongTinModel($dbConnection);
        $act = $_POST['act'] ?? '';

        if ($act === 'add_info') {
            $loai = trim($_POST['loai_thong_tin'] ?? '');
            $type = ($_POST['type'] ?? 'text') === 'link' ? 'link' : 'text';
            if ($loai === '') {
                $_SESSION['admin_info_error'] = 'Tên loại không được để trống.';
            } else {
                $thongTinModel->create($loai, $type);
                $newId = (int)$thongTinModel->getFirstByLoai($loai)['ma_thong_tin'];
                $thongTinModel->replaceChiTiet($newId, $_POST['chi_tiet'] ?? []);
                $_SESSION['admin_info_success'] = 'Đã thêm trường thông tin mới.';
            }
        } elseif ($act === 'update_info') {
            $id = (int)($_POST['id'] ?? 0);
            $loai = trim($_POST['loai_thong_tin'] ?? '');
            $type = ($_POST['type'] ?? 'text') === 'link' ? 'link' : 'text';
            if ($id <= 0 || $loai === '') {
                $_SESSION['admin_info_error'] = 'Dữ liệu không hợp lệ.';
            } else {
                $thongTinModel->update($id, $loai, $type);
                $thongTinModel->replaceChiTiet($id, $_POST['chi_tiet'] ?? []);
                $_SESSION['admin_info_success'] = 'Đã cập nhật thành công.';
            }
        } elseif ($act === 'delete_info') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $thongTinModel->deleteById($id);
                $_SESSION['admin_info_success'] = 'Đã xoá trường thông tin.';
            }
        }

        header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=info');
        exit;
    }

    if ($adminAction === 'qna') {
        $qnaCtrl = new QnAController($dbConnection);
        $act = $_POST['act'] ?? '';

        switch ($act) {
            case 'answer':
                $qId = (int)($_POST['id'] ?? 0);
                $result = $qnaCtrl->adminCreateAnswer($qId, $_POST['noi_dung'] ?? '', $adminUserId);
                if (isset($result['error'])) $_SESSION['admin_qna_error'] = $result['error'];
                else $_SESSION['admin_qna_success'] = 'Đã gửi trả lời thành công.';
                header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=qna&act=view&id=' . $qId);
                exit;

            case 'edit_answer':
                $aId = (int)($_POST['id'] ?? 0);
                $qId = (int)($_POST['question_id'] ?? 0);
                $result = $qnaCtrl->adminUpdateAnswer($aId, $_POST['noi_dung'] ?? '');
                if (isset($result['error'])) $_SESSION['admin_qna_error'] = $result['error'];
                else $_SESSION['admin_qna_success'] = 'Đã cập nhật trả lời.';
                header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=qna&act=view&id=' . $qId);
                exit;

            case 'delete':
                $qId = (int)($_POST['id'] ?? 0);
                $qnaCtrl->adminDeleteQuestion($qId);
                $_SESSION['admin_qna_success'] = 'Đã xoá câu hỏi.';
                header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=qna&act=questions');
                exit;

            case 'delete_answer':
                $aId = (int)($_POST['id'] ?? 0);
                $qId = (int)($_POST['question_id'] ?? 0);
                $qnaCtrl->adminDeleteAnswer($aId);
                $_SESSION['admin_qna_success'] = 'Đã xoá trả lời.';
                header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=qna&act=view&id=' . $qId);
                exit;

            case 'approve':
                $qId = (int)($_POST['id'] ?? 0);
                $qnaCtrl->adminUpdateStatus($qId, 'chua_tra_loi');
                $_SESSION['admin_qna_success'] = 'Đã duyệt câu hỏi.';
                header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=qna&act=questions');
                exit;

            case 'hide':
                $qId = (int)($_POST['id'] ?? 0);
                $qnaCtrl->adminUpdateStatus($qId, 'da_an');
                $_SESSION['admin_qna_success'] = 'Đã ẩn câu hỏi.';
                header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=qna&act=questions');
                exit;

            case 'unhide':
                $qId = (int)($_POST['id'] ?? 0);
                $qnaCtrl->adminUnhideQuestion($qId);
                $_SESSION['admin_qna_success'] = 'Đã hiện câu hỏi.';
                header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=qna&act=questions');
                exit;

            case 'create_faq':
                $result = $qnaCtrl->adminCreateFaq(
                    $_POST['ten_cau_hoi'] ?? '',
                    (int)($_POST['ma_loai'] ?? 0),
                    $adminUserId,
                    $_POST['noi_dung'] ?? ''
                );
                if (isset($result['error'])) $_SESSION['admin_qna_error'] = $result['error'];
                else $_SESSION['admin_qna_success'] = 'Đã tạo FAQ mới.';
                header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=qna&act=faq');
                exit;

            case 'add_category':
                $soThuTu = (int)($_POST['so_thu_tu'] ?? 0);
                $result = $qnaCtrl->adminCreateCategory($_POST['ten_loai'] ?? '', $soThuTu);
                if (isset($result['error'])) $_SESSION['admin_qna_error'] = $result['error'];
                else $_SESSION['admin_qna_success'] = 'Đã thêm chủ đề mới.';
                header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=qna&act=categories');
                exit;

            case 'edit_category':
                $id = (int)($_POST['id'] ?? 0);
                $newTenLoai = $_POST['ten_loai'] ?? '';
                $soThuTu = (int)($_POST['so_thu_tu'] ?? 0);
                $result = $qnaCtrl->adminUpdateCategory($id, $newTenLoai, $soThuTu);
                if (isset($result['error'])) $_SESSION['admin_qna_error'] = $result['error'];
                else $_SESSION['admin_qna_success'] = 'Đã cập nhật chủ đề.';
                header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=qna&act=categories');
                exit;

            case 'delete_category':
                $id = (int)($_POST['id'] ?? 0);
                $qnaCtrl->adminDeleteCategory($id);
                $_SESSION['admin_qna_success'] = 'Đã xoá chủ đề.';
                header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=qna&act=categories');
                exit;

            case 'reorder_categories':
                $order = isset($_POST['order']) ? json_decode($_POST['order'], true) : [];
                if (!is_array($order)) $order = [];
                $result = $qnaCtrl->adminReorderCategories($order);
                if (isset($result['error'])) $_SESSION['admin_qna_error'] = $result['error'];
                elseif (!empty($result['unchanged'])) $_SESSION['admin_qna_success'] = 'Thứ tự chủ đề không thay đổi.';
                else $_SESSION['admin_qna_success'] = 'Đã cập nhật thứ tự chủ đề.';
                header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=qna&act=categories');
                exit;
        }
    }
}

if ($page === 'login') {
    $authController->showLoginForm();
    exit;
} elseif ($page === 'register') {
    $authController->showRegisterForm();
    exit;
}

$qnaController = new QnAController($dbConnection);

// Routing cho các page thường
switch ($page) {
    case 'home':
        $view_content = '../app/views/pages/Home.php';
        $pageTitle = 'Trang chủ';
        break;
    case 'about':
        $aboutController = new AboutController($dbConnection);
        $aboutPage = $aboutController->getAboutPage();
        $view_content = '../app/views/pages/About.php';
        $pageTitle = 'Giới thiệu';
        break;
    case 'products':
        $view_content = '../app/views/pages/Products.php';
        $pageTitle = 'Sản phẩm';
        break;
    case 'news':
        $newsController = new NewsController($dbConnection);
        $newsAction = isset($_GET['news_action']) ? $_GET['news_action'] : 'index';

        // Xử lý gợi ý tìm kiếm
        if ($newsAction === 'search_ajax') {
            $newsController->searchAjax();
            exit;
        }

        // Xử lý trang Chi tiết bài viết
        if ($newsAction === 'detail' && isset($_GET['slug'])) {
            $newsController->detail(trim((string) $_GET['slug']));
            exit;
        }

        // Xử lý trang Danh sách bài viết
        if ($newsAction === 'list') {
            $newsController->listing();
            exit;
        }

        $newsController->index();
        exit;
        break;
    case 'qna':
        $qnaTab = isset($_GET['tab']) ? trim($_GET['tab']) : 'qna';
        $selectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0;
        $currentPage = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
        $itemsPerPage = 10;
        $qnaCategories = $qnaController->getCategories();
        if ($qnaTab === 'faq') {
            $faqData = $qnaController->getFaqItems($selectedCategory, $currentPage, $itemsPerPage);
            $faqItems = $faqData['items'];
            $faqPagination = [
                'currentPage' => $faqData['currentPage'],
                'totalPages' => $faqData['totalPages'],
                'totalItems' => $faqData['totalItems'],
                'itemsPerPage' => $faqData['itemsPerPage']
            ];
            $view_content = '../app/views/pages/FAQ.php';
            $pageTitle = 'FAQ';
        } elseif ($qnaTab === 'my') {
            if (!isset($_SESSION['userid'])) {
                header('Location: ' . BASE_URL . '/public/index.php?page=login&error=qna_login_required');
                exit;
            }
            $myData = $qnaController->getMyQuestions((int)$_SESSION['userid'], $currentPage, $itemsPerPage);
            $qnaItems = $myData['items'];
            $qnaPagination = [
                'currentPage' => $myData['currentPage'],
                'totalPages' => $myData['totalPages'],
                'totalItems' => $myData['totalItems'],
                'itemsPerPage' => $myData['itemsPerPage']
            ];
            $view_content = '../app/views/pages/My.php';
            $pageTitle = 'Câu hỏi của tôi';
        } elseif ($qnaTab === 'ask') {
            if (!isset($_SESSION['userid'])) {
                header('Location: ' . BASE_URL . '/public/index.php?page=login&error=qna_login_required');
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                    $_SESSION['qna_form_error'] = 'CSRF token không hợp lệ. Vui lòng thử lại.';
                    header('Location: ' . BASE_URL . '/public/index.php?page=qna&tab=ask');
                    exit;
                }

                $tenCauHoi = trim($_POST['ten_cau_hoi'] ?? '');
                $maLoai = (int)($_POST['ma_loai'] ?? 0);

                $errors = [];
                if ($tenCauHoi === '') {
                    $errors[] = 'Vui lòng nhập nội dung câu hỏi.';
                } elseif (strlen($tenCauHoi) < 10) {
                    $errors[] = 'Câu hỏi phải có ít nhất 10 ký tự.';
                } elseif (strlen($tenCauHoi) > 255) {
                    $errors[] = 'Câu hỏi không được vượt quá 255 ký tự.';
                }

                if ($maLoai <= 0) {
                    $errors[] = 'Vui lòng chọn chủ đề hợp lệ.';
                } else {
                    $allCategories = $qnaController->getCategories();
                    $categoryExists = false;
                    foreach ($allCategories as $cat) {
                        if ((int)$cat['ma_loai'] === $maLoai) {
                            $categoryExists = true;
                            break;
                        }
                    }
                    if (!$categoryExists) {
                        $errors[] = 'Chủ đề được chọn không tồn tại.';
                    }
                }

                if (!empty($_FILES['images']['name'][0])) {
                    $maxFiles = 5;
                    $maxSize = 3 * 1024 * 1024;
                    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
                    $fileCount = count(array_filter($_FILES['images']['name']));

                    if ($fileCount > $maxFiles) {
                        $errors[] = "Không được tải lên quá $maxFiles ảnh (hiện tại: $fileCount).";
                    }

                    for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
                        if (empty($_FILES['images']['name'][$i])) continue;
                        if ($_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) {
                            $errors[] = "Lỗi tải lên ảnh {$_FILES['images']['name'][$i]}: " .
                                ($_FILES['images']['error'][$i] === UPLOAD_ERR_INI_SIZE ?
                                'Tệp quá lớn' : 'Lỗi hệ thống');
                            continue;
                        }

                        $fileSize = $_FILES['images']['size'][$i];
                        $fileMime = mime_content_type($_FILES['images']['tmp_name'][$i]);

                        if ($fileSize > $maxSize) {
                            $errors[] = "Ảnh '{$_FILES['images']['name'][$i]}' vượt quá 3MB.";
                        }
                        if (!in_array($fileMime, $allowedMimes)) {
                            $errors[] = "Ảnh '{$_FILES['images']['name'][$i]}' không phải định dạng hỗ trợ (PNG, JPEG, WebP).";
                        }
                    }
                }

                if (!empty($errors)) {
                    $_SESSION['qna_form_error'] = implode(' ', $errors);
                    header('Location: ' . BASE_URL . '/public/index.php?page=qna&tab=ask');
                    exit;
                }

                $qnaController->createQuestion($tenCauHoi, $maLoai, (int)$_SESSION['userid']);
                $_SESSION['qna_form_success'] = 'Câu hỏi của bạn đã được ghi nhận. Chúng tôi sẽ cập nhật phần trả lời khi có phản hồi.';
                header('Location: ' . BASE_URL . '/public/index.php?page=qna');
                exit;
            }

            $qnaCategories = $qnaController->getCategories();
            $view_content = '../app/views/pages/Ask.php';
            $pageTitle = 'Đặt câu hỏi';
        } else {
            $qnaData = $qnaController->getQuestions($selectedCategory, $currentPage, $itemsPerPage);
            $qnaItems = $qnaData['items'];
            $qnaPagination = [
                'currentPage' => $qnaData['currentPage'],
                'totalPages' => $qnaData['totalPages'],
                'totalItems' => $qnaData['totalItems'],
                'itemsPerPage' => $qnaData['itemsPerPage']
            ];
            $view_content = '../app/views/pages/QnA.php';
            $pageTitle = 'Hỏi đáp';
        }
        break;
    case 'contact':
        $view_content = '../app/views/pages/Contact.php';
        $pageTitle = 'Liên hệ';
        break;
    case 'admin':
        $pageTitle = 'Admin Dashboard';
        break;
    default:
        $view_content = '../app/views/pages/404.php';
        $pageTitle = 'Lỗi 404';
        break;
}

// Admin gọi Controller
if ($page == 'admin') {
    $admin_action = isset($_GET['admin_action']) ? $_GET['admin_action'] : 'dashboard';

    require_once '../app/controllers/AdminNewsController.php';
    $newsController = new AdminNewsController($dbConnection);

    if ($admin_action === 'news') {
        $newsController->index();
        exit; // Lệnh exit báo PHP dừng lại vì trong index() đã gọi adminLayout
    } 
    elseif ($admin_action === 'news_store') {
        $newsController->store();
        exit; 
    } 
    elseif ($admin_action === 'news_edit') {
        $newsController->edit();
        exit; 
    } 
    elseif ($admin_action === 'news_update') {
        $newsController->update();
        exit;
    }
    elseif ($admin_action === 'news_delete') {
        $newsController->delete();
        exit;
    }
    elseif ($admin_action === 'news_upload_image') {
        $newsController->uploadImage();
        exit;
    }

    require_once '../app/views/admin/adminLayout.php';
} 
else {
    require_once '../app/views/template.php';
}
?>