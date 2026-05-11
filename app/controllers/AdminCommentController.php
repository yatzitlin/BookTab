<?php
require_once __DIR__ . '/BaseController.php';

class AdminCommentController extends BaseController {
    private $commentModel;
    private $categoryModel;

    public function __construct($dbConnection) {
        parent::__construct($dbConnection);
        $this->commentModel = $this->loadModel('CommentModel');
        $this->categoryModel = $this->loadModel('NewsCategoryModel');
    }

    // Hiển thị danh sách bình luận
    public function index() {
        $selectedCat = isset($_GET['cat']) ? (int) $_GET['cat'] : 0;
        
        $comments = $this->commentModel->getAllCommentsForAdmin($selectedCat);
        $categories = $this->categoryModel->getAllCategories();
        
        require_once __DIR__ . '/../views/admin/adminLayout.php';
    }

    // Đổi trạng thái hiển thị
    public function toggleComment() {
        if (!isset($_GET['id'])) {
            $_SESSION['admin_comment_error'] = 'ID bình luận không hợp lệ.';
            header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=comments');
            exit;
        }

        $id = (int) $_GET['id'];
        $result = $this->commentModel->toggleVisibility($id);

        if ($result) {
            $_SESSION['admin_comment_success'] = 'Đã cập nhật trạng thái bình luận.';
        } else {
            $_SESSION['admin_comment_error'] = 'Lỗi khi cập nhật trạng thái.';
        }

        header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=comments');
        exit;
    }

    // Xóa bình luận
    public function deleteComment() {
        if (!isset($_GET['id'])) {
            $_SESSION['admin_comment_error'] = 'ID bình luận không hợp lệ.';
            header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=comments');
            exit;
        }

        $id = (int) $_GET['id'];
        $result = $this->commentModel->deleteComment($id);

        if ($result) {
            $_SESSION['admin_comment_success'] = 'Đã xóa bình luận.';
        } else {
            $_SESSION['admin_comment_error'] = 'Lỗi khi xóa bình luận.';
        }

        header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=comments');
        exit;
    }
}
?>
