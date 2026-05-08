<?php
$current_page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<header class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-6xl mx-auto px-4 lg:px-8">
        <div class="flex items-center justify-between h-20">
            
            <!-- Logo -->
            <a href="<?php echo BASE_URL; ?>/public/index.php" class="text-2xl font-bold text-gray-900">
                Book<span class="text-red-500">Tab</span>
            </a>

            <!-- Navigation Links (Desktop) -->
            <nav class="hidden md:flex space-x-8">
                <a href="<?php echo BASE_URL; ?>/public/index.php?page=home" 
                   class="<?php echo $current_page === 'home' ? 'text-red-500 font-bold' : 'text-gray-600 hover:text-gray-900'; ?> transition">
                    Trang chủ
                </a>
                <a href="<?php echo BASE_URL; ?>/public/index.php?page=about" 
                   class="<?php echo $current_page === 'about' ? 'text-red-500 font-bold' : 'text-gray-600 hover:text-gray-900'; ?> transition">
                    Giới thiệu
                </a>
                <a href="<?php echo BASE_URL; ?>/public/index.php?page=products" 
                   class="<?php echo $current_page === 'products' ? 'text-red-500 font-bold' : 'text-gray-600 hover:text-gray-900'; ?> transition">
                    Sản phẩm
                </a>
                <a href="<?php echo BASE_URL; ?>/public/index.php?page=news" 
                   class="<?php echo $current_page === 'news' ? 'text-red-500 font-bold' : 'text-gray-600 hover:text-gray-900'; ?> transition">
                    Tin tức
                </a>
                <a href="<?php echo BASE_URL; ?>/public/index.php?page=qna" 
                   class="<?php echo $current_page === 'qna' ? 'text-red-500 font-bold' : 'text-gray-600 hover:text-gray-900'; ?> transition">
                    Hỏi đáp
                </a>
                <a href="<?php echo BASE_URL; ?>/public/index.php?page=contact" 
                   class="<?php echo $current_page === 'contact' ? 'text-red-500 font-bold' : 'text-gray-600 hover:text-gray-900'; ?> transition">
                    Liên hệ
                </a>
            </nav>

            <!-- Right Icons -->
            <div class="flex items-center space-x-6">
                <!-- Search Icon -->
                <button class="text-gray-600 hover:text-gray-900 text-lg">
                    <i class="fas fa-search"></i>
                </button>

                <!-- Shopping Cart -->
                <button class="relative text-gray-600 hover:text-gray-900 text-lg">
                    <i class="fas fa-shopping-cart"></i>
                    <span id="cartCount" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center">
                        0
                    </span>
                </button>

                <!-- User Menu -->
                <div class="relative group">
                    <button class="text-gray-600 hover:text-gray-900 text-lg">
                        <i class="fas fa-user"></i>
                    </button>
                    <div class="absolute right-0 w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition">
                        <?php if (isset($_SESSION['userid'])): ?>
                            <a href="<?php echo BASE_URL; ?>/public/index.php?page=profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user-circle mr-2"></i> <?php echo htmlspecialchars($_SESSION['ho_va_ten_dem'] . ' ' . $_SESSION['ten'], ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                            <a href="<?php echo BASE_URL; ?>/public/index.php?page=orders" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-shopping-bag mr-2"></i> Đơn hàng của tôi
                            </a>
                            <a href="<?php echo BASE_URL; ?>/public/index.php?action=logout" class="block px-4 py-2 text-red-600 hover:bg-red-50 border-t font-semibold">
                                <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
                            </a>
                        <?php else: ?>
                            <a href="<?php echo BASE_URL; ?>/public/index.php?action=login" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-in-alt mr-2"></i> Đăng nhập
                            </a>
                            <a href="<?php echo BASE_URL; ?>/public/index.php?action=register" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 border-t font-semibold text-red-600">
                                <i class="fas fa-user-plus mr-2"></i> Đăng ký
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Mobile Menu Toggle -->
                <button id="menuToggle" class="md:hidden text-gray-600 hover:text-gray-900 text-lg">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <nav id="navLinks" class="hidden md:hidden pb-4 space-y-2 border-t border-gray-200">
            <a href="<?php echo BASE_URL; ?>/public/index.php?page=home" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Trang chủ</a>
            <a href="<?php echo BASE_URL; ?>/public/index.php?page=about" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Giới thiệu</a>
            <a href="<?php echo BASE_URL; ?>/public/index.php?page=products" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Sản phẩm</a>
            <a href="<?php echo BASE_URL; ?>/public/index.php?page=news" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Tin tức</a>
            <a href="<?php echo BASE_URL; ?>/public/index.php?page=qna" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Hỏi đáp</a>
            <a href="<?php echo BASE_URL; ?>/public/index.php?page=contact" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Liên hệ</a>
        </nav>
    </div>
</header>

<script>
    // Mobile menu toggle
    const menuToggle = document.getElementById('menuToggle');
    const navLinks = document.getElementById('navLinks');
    
    menuToggle.addEventListener('click', function() {
        navLinks.classList.toggle('hidden');
    });

    // Update cart count from localStorage
    function updateCartCount() {
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const count = cart.reduce((sum, item) => sum + item.quantity, 0);
        document.getElementById('cartCount').textContent = count;
    }

    updateCartCount();
    window.addEventListener('storage', updateCartCount);
</script>
