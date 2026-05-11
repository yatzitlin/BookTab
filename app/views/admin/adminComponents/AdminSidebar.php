<?php
$adminAssetBase = (defined('BASE_URL') ? rtrim(BASE_URL, '/') : 'http://localhost/BookTab') . '/public/admin_assets';

$currentAction = isset($_GET['admin_action']) ? $_GET['admin_action'] : 'dashboard';
?>
<div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="index.html"><picture><source srcset="<?php echo htmlspecialchars($adminAssetBase, ENT_QUOTES, 'UTF-8'); ?>/images/icon/logo.avif" type="image/avif"><img src="<?php echo htmlspecialchars($adminAssetBase, ENT_QUOTES, 'UTF-8'); ?>/images/icon/logo.png" alt="logo"></picture></a>
        </div>
    </div>
    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">
                    <li class="<?php echo ($currentAction == 'dashboard') ? 'active' : ''; ?>">
                        <a href="javascript:void(0)" aria-expanded="<?php echo ($currentAction == 'dashboard') ? 'true' : 'false'; ?>">
                            <i class="ti-dashboard"></i><span>dashboard</span>
                        </a>
                        <ul class="collapse <?php echo ($currentAction == 'dashboard') ? 'in show' : ''; ?>">
                            <li class="<?php echo ($currentAction == 'dashboard') ? 'active' : ''; ?>">
                                <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=dashboard">AdminDashboard</a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="<?php echo ($currentAction == 'home') ? 'active' : ''; ?>">
                        <a href="javascript:void(0)" aria-expanded="<?php echo ($currentAction == 'home') ? 'true' : 'false'; ?>">
                            <i class="ti-layout-sidebar-left"></i><span>Home</span>
                        </a>
                        <ul class="collapse <?php echo ($currentAction == 'home') ? 'in show' : ''; ?>">
                            <li class="<?php echo ($currentAction == 'home') ? 'active' : ''; ?>">
                                <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=home">AdminHome</a>
                            </li>
                        </ul>
                    </li>

                    <li class="<?php echo ($currentAction == 'products') ? 'active' : ''; ?>">
                        <a href="javascript:void(0)" aria-expanded="<?php echo ($currentAction == 'products') ? 'true' : 'false'; ?>">
                            <i class="ti-pie-chart"></i><span>Products</span>
                        </a>
                        <ul class="collapse <?php echo ($currentAction == 'products') ? 'in show' : ''; ?>">
                            <li class="<?php echo ($currentAction == 'products') ? 'active' : ''; ?>">
                                <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=products">AdminProducts</a>
                            </li>
                        </ul>
                    </li>

                    <li class="<?php echo ($currentAction == 'orders') ? 'active' : ''; ?>">
                        <a href="javascript:void(0)" aria-expanded="<?php echo ($currentAction == 'orders') ? 'true' : 'false'; ?>">
                            <i class="ti-shopping-cart"></i><span>Orders</span>
                        </a>
                        <ul class="collapse <?php echo ($currentAction == 'orders') ? 'in show' : ''; ?>">
                            <li class="<?php echo ($currentAction == 'orders') ? 'active' : ''; ?>">
                                <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=orders">AdminOrders</a>
                            </li>
                        </ul>
                    </li>

                    <li class="<?php echo ($currentAction == 'news') ? 'active' : ''; ?>">
                        <a href="javascript:void(0)" aria-expanded="<?php echo ($currentAction == 'news') ? 'true' : 'false'; ?>">
                            <i class="ti-widget"></i><span>News</span>
                        </a>
                        <ul class="collapse <?php echo ($currentAction == 'news') ? 'in show' : ''; ?>">
                            <li class="<?php echo ($currentAction == 'news') ? 'active' : ''; ?>">
                                <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=news">AdminNews</a>
                            </li>
                        </ul>
                    </li>

                    <li class="<?php echo ($currentAction == 'qna') ? 'active' : ''; ?>">
                        <a href="javascript:void(0)" aria-expanded="<?php echo ($currentAction == 'qna') ? 'true' : 'false'; ?>">
                            <i class="ti-layout-sidebar-right"></i><span>QnA</span>
                        </a>
                        <ul class="collapse <?php echo ($currentAction == 'qna') ? 'in show' : ''; ?>">
                            <li class="<?php echo ($currentAction == 'qna') ? 'active' : ''; ?>">
                                <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna">AdminQnA</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>