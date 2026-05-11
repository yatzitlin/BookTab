<?php
require_once __DIR__ . '/BaseController.php';

class NewsController extends BaseController {
    private $newsModel;
    private $categoryModel;

    public function __construct($dbConnection) {
        parent::__construct($dbConnection);
        $this->newsModel = $this->loadModel('NewsModel');
        $this->categoryModel = $this->loadModel('NewsCategoryModel');
    }

    private function renderNotFound() {
        $view_content = __DIR__ . '/../views/pages/404.php';
        $pageTitle = 'Lỗi 404';
        require_once __DIR__ . '/../views/template.php';
    }

    // Hiển thị trang Danh sách bài viết
    public function index() {
        $latestNews = $this->newsModel->getLatestPublishedNews(5);
        $categoriesWithNews = $this->categoryModel->getCategoriesWithTopNews(4);
        $trendingNews = $this->newsModel->getTrendingNews(5);

        $view_content = __DIR__ . '/../views/pages/News.php';
        $pageTitle = 'Tin tức';
        require_once __DIR__ . '/../views/template.php';
    }

    // Live search AJAX
    public function searchAjax() {
        $keyword = trim((string) ($_GET['keyword'] ?? ''));
        $results = [];

        if ($keyword !== '') {
            $results = $this->newsModel->searchPublishedNewsSuggestions($keyword, 6);
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'keyword' => $keyword,
            'count' => count($results),
            'results' => array_map(function ($news) {
                return [
                    'ma_bai_viet' => (int) $news['ma_bai_viet'],
                    'tieu_de' => $news['tieu_de'],
                    'thumbnail_url' => $news['thumbnail_url'] ?? '',
                    'ten_loai' => $news['ten_loai'] ?? '',
                    'slug' => $news['slug'] ?? '',
                    'ngay_dang' => $news['ngay_dang'] ?? '',
                    'ho_ten' => $news['ho_ten'] ?? '',
                ];
            }, $results)
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Trang xem toàn bộ danh mục / tìm kiếm
    public function listing() {
        $type = $_GET['type'] ?? 'all';
        $currentPage = max(1, (int) ($_GET['news_page'] ?? 1));
        $limit = 6;
        $offset = ($currentPage - 1) * $limit;

        $latestNews = $this->newsModel->getLatestPublishedNews(5);
        $categoriesWithNews = $this->categoryModel->getCategoriesWithTopNews(4);
        $trendingNews = $this->newsModel->getTrendingNews(5);

        $selectedCategory = null;
        $searchKeyword = '';
        $newsItems = [];
        $totalItems = 0;
        $pageTitle = 'Bài viết';

        if ($type === 'category') {
            $categorySlug = trim((string) ($_GET['slug'] ?? ''));
            $selectedCategory = $categorySlug !== '' ? $this->categoryModel->getCategoryBySlug($categorySlug) : null;

            if (!$selectedCategory) {
                $this->renderNotFound();
                return;
            }

            $newsItems = $this->newsModel->getPublishedNewsByCategorySlug($categorySlug, $limit, $offset);
            $totalItems = $this->newsModel->countPublishedNewsByCategorySlug($categorySlug);
            $pageTitle = 'Danh mục: ' . $selectedCategory['ten_loai'];
        } elseif ($type === 'search') {
            $searchKeyword = trim((string) ($_GET['keyword'] ?? ''));

            if ($searchKeyword !== '') {
                // Nếu có nhập từ khóa -> Tìm kiếm bình thường
                $newsItems = $this->newsModel->searchPublishedNews($searchKeyword, $limit, $offset);
                $totalItems = $this->newsModel->countPublishedNewsByKeyword($searchKeyword);
                $pageTitle = 'Tìm kiếm: ' . $searchKeyword;
            } else {
                // Nếu không có từ khóa -> Hiển thị tất cả bài viết
                $newsItems = $this->newsModel->searchPublishedNews('', $limit, $offset);
                $totalItems = $this->newsModel->countPublishedNewsByKeyword('');
                $pageTitle = 'Tất cả bài viết';
            }
        }

        $totalPages = max(1, (int) ceil($totalItems / $limit));

        $view_content = __DIR__ . '/../views/pages/NewsList.php';
        require_once __DIR__ . '/../views/template.php';
    }

    // Chi tiết 1 danh mục
    public function category($id) {
        $selectedCategory = $this->categoryModel->getCategoryById($id);
        
        if (!$selectedCategory) {
            $this->renderNotFound();
            return;
        }

        $latestNews = $this->newsModel->getLatestPublishedNews(5);
        $categoriesWithNews = $this->categoryModel->getCategoriesWithTopNews(4);
        $trendingNews = $this->newsModel->getTrendingNews(5);
        $newsByCategory = $this->newsModel->getPublishedNewsByCategoryId($id);

        $view_content = __DIR__ . '/../views/pages/News.php';
        $pageTitle = 'Danh mục: ' . $selectedCategory['ten_loai'];
        require_once __DIR__ . '/../views/template.php';
    }

    // Chi tiết 1 bài viết
    public function detail($slug) {
        $newsDetail = $this->newsModel->getPublishedNewsBySlug($slug);

        // Không tồn tại bài viết
        if (!$newsDetail) {
            $this->renderNotFound();
            return;
        }

        $id = (int) $newsDetail['ma_bai_viet'];

        // Cập nhật lượt xem: Mỗi user khi bấm vào bài viết -> chỉ được tính là 1 lần duy nhất
        // Kiểm tra Session đã được khởi động
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Tạo một mảng trong Session để lưu ID các bài viết người này đã đọc
        if (!isset($_SESSION['da_xem_bai_viet'])) {
            $_SESSION['da_xem_bai_viet'] = [];
        }
            
        // Kiểm tra xem ID bài viết hiện tại đã có trong mảng Session chưa
        if (!in_array($id, $_SESSION['da_xem_bai_viet'])) {
            // Nếu chưa có -> Đây là lần đọc đầu tiên trong session
            // +1 lượt xem
            $this->newsModel->increaseViewCount($id);
            
            // Truyền ID bài này vào mảng để đánh dấu là "đã xem"
            $_SESSION['da_xem_bai_viet'][] = $id;
        }

        $view_content = __DIR__ . '/../views/pages/NewsDetail.php';
        $pageTitle = $newsDetail['tieu_de'];
        require_once __DIR__ . '/../views/template.php';
    }

    // AJAX: Lấy bình luận (top-level + replies)
    public function getCommentsAjax() {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $ma_bai_viet = (int) ($_GET['article_id'] ?? 0);
            $page = max(1, (int) ($_GET['comment_page'] ?? 1));
            $limit = 10;
            $offset = ($page - 1) * $limit;

            if ($ma_bai_viet <= 0) {
                echo json_encode(['success' => false, 'error' => 'Invalid article']);
                exit;
            }

            $commentModel = $this->loadModel('CommentModel');

            // Lấy bình luận top-level
            $topComments = $commentModel->getTopLevelComments($ma_bai_viet, $limit, $offset);
            $totalComments = $commentModel->countTopLevelComments($ma_bai_viet);

            // Lấy replies cho mỗi top-level comment
            foreach ($topComments as &$comment) {
                $comment['replies'] = $commentModel->getRepliesByParentId($comment['ma_binh_luan']);
            }
            unset($comment);

            echo json_encode([
                'success' => true,
                'comments' => $topComments,
                'total' => $totalComments,
                'page' => $page,
                'hasMore' => ($offset + $limit) < $totalComments
            ], JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Throwable $e) {
            error_log('getCommentsAjax failed: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => 'Không tải được bình luận.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    // AJAX: Post bình luận mới
    public function postCommentAjax() {
        header('Content-Type: application/json; charset=utf-8');

        // Kiểm tra session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['userid'])) {
            echo json_encode(['error' => 'Bạn cần đăng nhập để bình luận']);
            exit;
        }

        $ma_bai_viet = (int) ($_POST['article_id'] ?? 0);
        $noi_dung = trim($_POST['content'] ?? '');
        $parent_id = isset($_POST['parent_id']) ? (int) $_POST['parent_id'] : null;

        if ($parent_id !== null && $parent_id <= 0) {
            $parent_id = null;
        }

        if ($ma_bai_viet <= 0 || $noi_dung === '') {
            echo json_encode(['error' => 'Dữ liệu không hợp lệ']);
            exit;
        }

        if (strlen($noi_dung) < 5 || strlen($noi_dung) > 5000) {
            echo json_encode(['error' => 'Bình luận phải từ 5-5000 ký tự']);
            exit;
        }

        $commentModel = $this->loadModel('CommentModel');
        $commentId = $commentModel->insertComment($ma_bai_viet, (int) $_SESSION['userid'], $noi_dung, $parent_id);

        if ($commentId) {
            $newComment = $commentModel->getCommentById($commentId);
            echo json_encode([
                'success' => true,
                'comment' => $newComment
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['error' => 'Lỗi khi lưu bình luận']);
        }
        exit;
    }
}
?>