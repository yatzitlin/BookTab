<?php
require_once __DIR__ . '/BaseController.php';

class ProductController extends BaseController {
    private $productModel;

    public function __construct($dbConnection) {
        parent::__construct($dbConnection);
        $this->productModel = $this->loadModel('ProductModel');
    }

    public function showProducts() {
        $keyword    = trim($_GET['search'] ?? ''); // lấy keyword search
        $categoryId = $_GET['category'] ?? null; // lấy category id
        $page       = max(1, intval($_GET['p'] ?? 1)); // lấy số page
        $limit      = 12; // page limit
        $products      = $this->productModel->getProducts($page, $limit, $keyword, $categoryId);
        $totalProducts = $this->productModel->countProducts($keyword, $categoryId);
        $totalPages    = ceil($totalProducts / $limit); // 15/12 = 1.25 -> 2
        $categories    = $this->productModel->getAllCategories();
        $view_content = dirname(__FILE__) . '/../views/pages/Products.php';
        $pageTitle    = 'Sản phẩm';
        require_once dirname(__FILE__) . '/../views/template.php';
    }
    public function showProductDetail() {
        $id = intval($_GET['id'] ?? 0); // ép kiểu int
        if (!$id) {
            // id sai thì chuyển về trang danh sách
            header('Location: ' . BASE_URL . '/public/index.php?page=products');
            exit;
        }
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            $view_content = dirname(__FILE__) . '/../views/pages/404.php';
            $pageTitle    = 'Không tìm thấy';
            require_once dirname(__FILE__) . '/../views/template.php';
            return;
        }
        $images     = $this->productModel->getProductImages($id);
        $reviews    = $this->productModel->getProductReviews($id);
        $ratingInfo = $this->productModel->getAverageRating($id);
        $view_content = dirname(__FILE__) . '/../views/pages/ProductDetail.php';
        $pageTitle    = htmlspecialchars($product['ten_san_pham'], ENT_QUOTES, 'UTF-8');
        require_once dirname(__FILE__) . '/../views/template.php';
    }
    public function handleAddReview() {

        // chỉ được post
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
            header('Location: ' . BASE_URL . '/public/index.php?page=products'); exit; 
        }

        // user chưa đăng nhập
        if (!isset($_SESSION['userid'])) { 
            header('Location: ' . BASE_URL . '/public/index.php?page=login'); exit; 
        }

        // chặn csrf
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            header('Location: ' . BASE_URL . '/public/index.php?page=products'); exit;
        }

        $productId = intval($_POST['product_id'] ?? 0);
        $score     = max(1, min(5, intval($_POST['diem'] ?? 5)));
        $content   = trim($_POST['noi_dung'] ?? '');
        $this->productModel->addReview($_SESSION['userid'], $productId, $score, $content);
        header('Location: ' . BASE_URL . '/public/index.php?page=product_detail&id=' . $productId);
        exit;
    }
    // ADMIN
    public function showAdminProducts() {
        $keyword = trim($_GET['search'] ?? '');
        $page    = max(1, intval($_GET['p'] ?? 1));
        $limit   = 20;
        $products      = $this->productModel->getAllProductsAdmin($page, $limit, $keyword);
        $totalProducts = $this->productModel->countProductsAdmin($keyword);
        $totalPages    = ceil($totalProducts / $limit);
        $categories    = $this->productModel->getAllCategories();
        include dirname(__FILE__) . '/../views/admin/adminPages/AdminProducts.php';
    }
    public function handleAdminProductAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) return;
        $action = $_POST['product_action'] ?? '';
        if ($action === 'add') {
            $name  = trim($_POST['ten_san_pham'] ?? '');
            $desc  = trim($_POST['mo_ta'] ?? '');
            $price = floatval($_POST['gia_san_pham'] ?? 0);
            $catId = intval($_POST['ma_loai'] ?? 0) ?: null;
            if (!empty($name) && $price > 0) {
                $result = $this->productModel->addProduct($name, $desc, $price, $catId);
                // Xử lý upload ảnh
                if ($result['success'] && isset($_FILES['anh_san_pham']) && $_FILES['anh_san_pham']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['anh_san_pham'];
                    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
                    $maxSize = 2 * 1024 * 1024; // 2MB
                    if (in_array($file['type'], $allowed) && $file['size'] <= $maxSize) {
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $filename = 'product_' . $result['product_id'] . '_' . time() . '.' . $ext;
                        $uploadDir = dirname(__FILE__) . '/../../public/uploads/products/';
                        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                        move_uploaded_file($file['tmp_name'], $uploadDir . $filename);
                        $urlAnh = 'uploads/products/' . $filename;
                        $this->productModel->addProductImage($result['product_id'], $urlAnh, $name, true);
                    }
                }
            }
        } elseif ($action === 'edit') {
            $id       = intval($_POST['ma_san_pham'] ?? 0);
            $name     = trim($_POST['ten_san_pham'] ?? '');
            $desc     = trim($_POST['mo_ta'] ?? '');
            $price    = floatval($_POST['gia_san_pham'] ?? 0);
            $catId    = intval($_POST['ma_loai'] ?? 0) ?: null;
            $isActive = intval($_POST['is_active'] ?? 1);
            if ($id && !empty($name)) {
                $this->productModel->updateProduct($id, $name, $desc, $price, $catId, $isActive);
            }
        } elseif ($action === 'delete') {
            $id = intval($_POST['ma_san_pham'] ?? 0);
            if ($id) $this->productModel->deleteProduct($id);
        }
        header('Location: ' . BASE_URL . '/public/index.php?page=admin&admin_action=products');
        exit;
    }

}

?>