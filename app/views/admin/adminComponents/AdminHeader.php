<?php
$adminAssetBase = (defined('BASE_URL') ? rtrim(BASE_URL, '/') : 'http://localhost/BookTab') . '/public/admin_assets';
$adminPageTitle = $adminPageTitle ?? 'Dashboard';
$adminPageHeading = $adminPageHeading ?? $adminPageTitle;
$adminPageBreadcrumb = $adminPageBreadcrumb ?? $adminPageTitle;
?>
<div class="header-area">
    <div class="row align-items-center">
        <div class="col-md-6 col-sm-8 clearfix">
            <div class="nav-btn float-start">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="search-box float-start">
                <form action="#">
                    <input type="text" name="search" placeholder="Search..." required>
                    <i class="ti-search"></i>
                </form>
            </div>
        </div>
        <div class="col-md-6 col-sm-4 clearfix">
            <ul class="notification-area float-end">
                <li id="full-view"><i class="ti-fullscreen"></i></li>
                <li id="full-view-exit"><i class="ti-zoom-out"></i></li>                
            </ul>
        </div>
    </div>
</div>
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h1 class="page-title float-start"><?php echo htmlspecialchars($adminPageHeading, ENT_QUOTES, 'UTF-8'); ?></h1>
                <ul class="breadcrumbs float-start">
                    <li><a href="<?php echo BASE_URL; ?>/public/index.php?page=admin">Home</a></li>
                    <li><span><?php echo htmlspecialchars($adminPageBreadcrumb, ENT_QUOTES, 'UTF-8'); ?></span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            <div class="user-profile float-end">
                <picture><source srcset="<?php echo htmlspecialchars($adminAssetBase, ENT_QUOTES, 'UTF-8'); ?>/images/author/avatar.avif" type="image/avif"><img class="avatar user-thumb" src="<?php echo htmlspecialchars($adminAssetBase, ENT_QUOTES, 'UTF-8'); ?>/images/author/avatar.png" alt="avatar"></picture>
                <h4 class="user-name dropdown-toggle" data-bs-toggle="dropdown">
                    <?php echo htmlspecialchars($_SESSION['ho_va_ten_dem'] . ' ' . $_SESSION['ten'], ENT_QUOTES, 'UTF-8'); ?>    
                    <i class="fa-solid fa-angle-down"></i></h4>                
                <div class="dropdown-menu user-dropdown">
                    <a class="dropdown-item" href="#"><i class="fa-solid fa-user"></i> My Profile</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item user-dropdown-logout" href="<?php echo BASE_URL; ?>/public/index.php?action=logout"><i class="fa-solid fa-right-from-bracket"></i> Log Out</a>
                </div>
            </div>
        </div>
    </div>
</div>