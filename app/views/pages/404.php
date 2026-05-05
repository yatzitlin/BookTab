<?php
/**
 * 404 Error Page
 */
?>

<div class="flex flex-col items-center justify-center min-h-96 text-center py-20">
    <!-- 404 Icon -->
    <div class="mb-8">
        <i class="fas fa-exclamation-triangle text-7xl text-red-500"></i>
    </div>

    <!-- Heading -->
    <h1 class="text-6xl font-bold text-gray-900 mb-4">404</h1>
    <p class="text-2xl font-bold text-gray-700 mb-4">Trang không tìm thấy</p>

    <!-- Description -->
    <p class="text-gray-600 text-lg mb-8 max-w-md">
        Rất tiếc, trang bạn đang tìm kiếm không tồn tại hoặc đã bị xóa. 
        Vui lòng quay trở lại trang chủ hoặc sử dụng menu điều hướng.
    </p>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4">
        <a href="<?php echo BASE_URL; ?>/public/index.php?page=home" class="inline-block bg-red-500 text-white px-8 py-3 rounded-full font-bold hover:bg-red-600 transition">
            <i class="fas fa-home mr-2"></i> Trang chủ
        </a>
        <a href="<?php echo BASE_URL; ?>/public/index.php?page=products" class="inline-block bg-gray-200 text-gray-900 px-8 py-3 rounded-full font-bold hover:bg-gray-300 transition">
            <i class="fas fa-shopping-bag mr-2"></i> Sản phẩm
        </a>
    </div>

    <!-- Search Box -->
    <div class="mt-12 w-full max-w-md">
        <p class="text-gray-600 mb-4 font-semibold">Hoặc tìm kiếm sách:</p>
        <form class="flex gap-2">
            <input 
                type="text" 
                placeholder="Tìm kiếm sách..." 
                class="flex-1 px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-red-500 focus:outline-none transition"
            >
            <button type="submit" class="bg-red-500 text-white px-6 py-3 rounded-lg font-bold hover:bg-red-600 transition">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</div>
