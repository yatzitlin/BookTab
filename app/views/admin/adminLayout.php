<?php
$adminAssetBase = (defined('BASE_URL') ? rtrim(BASE_URL, '/') : 'http://localhost/BookTab') . '/public/admin_assets';
$admin_action = isset($_GET['admin_action']) ? $_GET['admin_action'] : 'dashboard';
$adminPages = array(
    'dashboard' => array('document_title' => 'Dashboard', 'heading' => 'Dashboard', 'breadcrumb' => 'Dashboard', 'file' => '/adminPages/AdminDashboard.php'),
    'home' => array('document_title' => 'AdminHome', 'heading' => 'AdminHome', 'breadcrumb' => 'AdminHome', 'file' => '/adminPages/AdminHome.php'),
    'products' => array('document_title' => 'AdminProducts', 'heading' => 'AdminProducts', 'breadcrumb' => 'AdminProducts', 'file' => '/adminPages/AdminProducts.php'),
    'news' => array('document_title' => 'AdminNews', 'heading' => 'AdminNews', 'breadcrumb' => 'AdminNews', 'file' => '/adminPages/AdminNews.php'),
    'qna' => array('document_title' => 'AdminQnA', 'heading' => 'AdminQnA', 'breadcrumb' => 'AdminQnA', 'file' => '/adminPages/AdminQnA.php'),
    'contact' => array('document_title' => 'AdminContact', 'heading' => 'AdminContact', 'breadcrumb' => 'AdminContact', 'file' => '/adminPages/AdminContact.php')
);
$adminPage = isset($adminPages[$admin_action]) ? $adminPages[$admin_action] : $adminPages['dashboard'];
$adminPageTitle = $adminPage['document_title'];
$adminPageHeading = $adminPage['heading'];
$adminPageBreadcrumb = $adminPage['breadcrumb'];
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
            <?php include __DIR__ . '/adminComponents/AdminHeader.php'; ?>
            <?php include __DIR__ . $adminPage['file']; ?>
        </div>

        <?php include __DIR__ . '/adminComponents/AdminFooter.php'; ?>
    </div>
</body>
</html>
