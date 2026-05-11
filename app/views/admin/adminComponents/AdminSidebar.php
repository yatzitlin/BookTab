<?php
$adminAssetBase = (defined('BASE_URL') ? rtrim(BASE_URL, '/') : 'http://localhost/BookTab') . '/public/admin_assets';

$currentAction = isset($_GET['admin_action']) ? $_GET['admin_action'] : 'home';
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

                    <li class="<?php echo ($currentAction == 'news') ? 'active' : ''; ?>">
                        <a href="javascript:void(0)" aria-expanded="<?php echo ($currentAction == 'news') ? 'true' : 'false'; ?>">
                            <i class="ti-widget"></i><span>News</span>
                        </a>
                        <ul class="collapse <?php echo ($currentAction == 'news') ? 'in show' : ''; ?>">
                            <li class="<?php echo ($currentAction == 'news') ? 'active' : ''; ?>">
                                <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=news">AdminNews</a>
                            </li>
                            <li class="<?php echo ($currentAction == 'comments') ? 'active' : ''; ?>">
                                <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=comments">CommentManages</a>
                            </li>
                        </ul>
                    </li>

                    <li class="<?php echo ($currentAction == 'users') ? 'active' : ''; ?>">
                        <a href="javascript:void(0)" aria-expanded="<?php echo ($currentAction == 'users') ? 'true' : 'false'; ?>">
                            <i class="ti-user"></i><span>UserManage</span>
                        </a>
                        <ul class="collapse <?php echo ($currentAction == 'users') ? 'in show' : ''; ?>">
                            <li class="<?php echo ($currentAction == 'users') ? 'active' : ''; ?>">
                                <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=users">AdminUserManage</a>
                            </li>
                        </ul>
                    </li>

                    <li class="<?php echo ($currentAction == 'qna') ? 'active' : ''; ?>">
                        <a href="javascript:void(0)" aria-expanded="<?php echo ($currentAction == 'qna') ? 'true' : 'false'; ?>">
                            <i class="ti-layout-sidebar-right"></i><span>Hỏi đáp</span>
                        </a>
                        <ul class="collapse <?php echo ($currentAction == 'qna') ? 'in show' : ''; ?>">
                            <li>
                                <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna&act=questions">Câu hỏi</a>
                            </li>
                            <li>
                                <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=qna&act=faq">FAQ</a>
                            </li>
                        </ul>
                    </li>

                    <li class="<?php echo ($currentAction == 'contact') ? 'active' : ''; ?>">
                        <a href="javascript:void(0)" aria-expanded="<?php echo ($currentAction == 'contact') ? 'true' : 'false'; ?>">
                            <i class="ti-email"></i><span>Contact</span>
                        </a>
                        <ul class="collapse <?php echo ($currentAction == 'contact') ? 'in show' : ''; ?>">
                            <li class="<?php echo ($currentAction == 'contact') ? 'active' : ''; ?>">
                                <a href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=contact">AdminContact</a>
                            </li>
                        </ul>

                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>