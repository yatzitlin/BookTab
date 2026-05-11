<?php
$adminAssetBase = (defined('BASE_URL') ? rtrim(BASE_URL, '/') : 'http://localhost/BookTab') . '/public/admin_assets';
$admin_action = isset($_GET['admin_action']) ? $_GET['admin_action'] : 'about';
if ($admin_action == 'contact') {

    require_once "../app/models/ContactModel.php";

    $contactModel = new ContactModel($dbConnection);

    $contacts = $contactModel->getAll();
}
$adminPages = array(
    'home' => array('document_title' => 'AdminHome', 'heading' => 'AdminHome', 'breadcrumb' => 'AdminHome', 'file' => '/adminPages/AdminHome.php'),
    'products' => array('document_title' => 'AdminProducts', 'heading' => 'AdminProducts', 'breadcrumb' => 'AdminProducts', 'file' => '/adminPages/AdminProducts.php'),
    'news' => array('document_title' => 'AdminNews', 'heading' => 'AdminNews', 'breadcrumb' => 'AdminNews', 'file' => '/adminPages/AdminNews.php'),
    'users' => array('document_title' => 'AdminUserManage', 'heading' => 'AdminUserManage', 'breadcrumb' => [['label'=>'UserManage']], 'file' => '/adminPages/AdminUserManage.php'),
    'comments' => array('document_title' => 'Quản lý Bình luận', 'heading' => 'Quản lý Bình luận', 'breadcrumb' => [['label'=>'Bình luận']], 'file' => '/adminPages/AdminComment.php'),
    'about' => array('document_title' => 'Giới thiệu', 'heading' => 'Quản lý Giới thiệu', 'breadcrumb' => [['label'=>'Giới thiệu','url'=>BASE_URL.'/public/index.php?page=about']], 'file' => '/adminPages/AdminAbout.php'),
    'qna' => array('document_title' => 'Hỏi đáp', 'heading' => 'Quản lý Hỏi đáp', 'breadcrumb' => [['label'=>'Hỏi đáp']], 'file' => '/adminPages/AdminQnA.php'),
    'info' => array('document_title' => 'Thông tin', 'heading' => 'Chỉnh sửa thông tin trang web', 'breadcrumb' => [['label'=>'Thông tin']], 'file' => '/adminPages/AdminInfo.php'),
    'contact' => array('document_title' => 'AdminContact', 'heading' => 'AdminContact', 'breadcrumb' => 'AdminContact', 'file' => '/adminPages/AdminContact.php'),
    'orders' => array('document_title' => 'AdminOrders', 'heading' => 'AdminOrders', 'breadcrumb' => 'AdminOrders', 'file' => '/adminPages/AdminOrders.php')
);
$adminPage = isset($adminPages[$admin_action]) ? $adminPages[$admin_action] : $adminPages['home'];
$adminPageTitle = $adminPage['document_title'];
$adminPageHeading = $adminPage['heading'];
$adminPageBreadcrumb = $adminPage['breadcrumb'];
// Cho phép các trang con override breadcrumb trước khi Header được include
$adminPageBreadcrumbOverride = null;
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>srtdash - <?php echo htmlspecialchars($adminPageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Ecommerce dashboard with order tracking, revenue charts, and customer activity overview.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?php echo htmlspecialchars($adminAssetBase, ENT_QUOTES, 'UTF-8'); ?>/images/icon/logo.png">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($adminAssetBase, ENT_QUOTES, 'UTF-8'); ?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($adminAssetBase, ENT_QUOTES, 'UTF-8'); ?>/css/fontawesome.min.css">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($adminAssetBase, ENT_QUOTES, 'UTF-8'); ?>/css/themify-icons.css">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($adminAssetBase, ENT_QUOTES, 'UTF-8'); ?>/css/metismenujs.min.css">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($adminAssetBase, ENT_QUOTES, 'UTF-8'); ?>/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <link rel="stylesheet" href="<?php echo htmlspecialchars($adminAssetBase, ENT_QUOTES, 'UTF-8'); ?>/css/typography.css">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($adminAssetBase, ENT_QUOTES, 'UTF-8'); ?>/css/default-css.css">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($adminAssetBase, ENT_QUOTES, 'UTF-8'); ?>/css/styles.css">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($adminAssetBase, ENT_QUOTES, 'UTF-8'); ?>/css/responsive.css">
</head>

<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>
    <div id="preloader"><div class="loader"></div></div>
    
    <div class="page-container">
        <?php include __DIR__ . '/adminComponents/AdminSidebar.php'; ?>

        <div class="main-content">
            <?php
            // Include trang con trước để nó có thể set $adminPageBreadcrumbOverride
            ob_start();
            include __DIR__ . $adminPage['file'];
            $pageContent = ob_get_clean();
            // Áp dụng override nếu có
            if (!empty($adminPageBreadcrumbOverride)) {
                $adminPageBreadcrumb = $adminPageBreadcrumbOverride;
            }
            ?>
            <?php include __DIR__ . '/adminComponents/AdminHeader.php'; ?>
            <?php echo $pageContent; ?>
        </div>

        <?php include __DIR__ . '/adminComponents/AdminFooter.php'; ?>
    </div>
</body>
</html>
