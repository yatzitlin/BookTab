<div class="flex flex-col md:flex-row gap-8">
    <!-- Sidebar danh mục -->
    <aside class="w-full md:w-64 shrink-0">
        <h3 class="text-lg font-bold mb-4">Danh mục</h3>
        <ul class="space-y-2">
            <li>
                <a href="<?php echo BASE_URL; ?>/public/index.php?page=products"
                   class="block px-3 py-2 rounded <?php echo empty($_GET['category']) ? 'bg-red-500 text-white' : 'hover:bg-gray-100'; ?>">
                    Tất cả
                </a>
            </li>
            <?php foreach ($categories as $cat): ?>
            <li>
                <a href="<?php echo BASE_URL; ?>/public/index.php?page=products&category=<?php echo $cat['ma_loai']; ?>"
                   class="block px-3 py-2 rounded <?php echo (isset($_GET['category']) && $_GET['category'] == $cat['ma_loai']) ? 'bg-red-500 text-white' : 'hover:bg-gray-100'; ?>">
                    <?php echo htmlspecialchars($cat['ten_loai'], ENT_QUOTES, 'UTF-8'); ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </aside>

    <!-- Main content -->
    <div class="flex-1">
        <!-- Thanh tìm kiếm -->
        <!-- search input -->
        <form method="GET" action="<?php echo BASE_URL; ?>/public/index.php" class="flex gap-2 mb-6">
            <input type="hidden" name="page" value="products">
            <input type="text" name="search" value="<?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>"
                   placeholder="Tìm kiếm sản phẩm..." class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:border-red-500">
            <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600">
                <i class="fas fa-search"></i> Tìm
            </button>
        </form>

        <?php if (!empty($keyword)): ?>
            <p class="mb-4 text-gray-600">Kết quả cho: "<strong><?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?></strong>" (<?php echo $totalProducts; ?> sản phẩm)</p>
        <?php endif; ?>

        <!-- Grid sản phẩm -->
        <?php if (empty($products)): ?>
            <p class="text-center text-gray-500 py-12">Không tìm thấy sản phẩm nào.</p>
        <?php else: ?>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($products as $p): ?>
            <a href="<?php echo BASE_URL; ?>/public/index.php?page=product_detail&id=<?php echo $p['ma_san_pham']; ?>"
               class="group border rounded-lg overflow-hidden hover:shadow-lg transition">
                <div class="aspect-square bg-gray-100 overflow-hidden">
                    <?php if (!empty($p['anh_chinh'])): ?>
                        <img src="<?php echo htmlspecialchars($p['anh_chinh'], ENT_QUOTES, 'UTF-8'); ?>"
                             alt="<?php echo htmlspecialchars($p['ten_san_pham'], ENT_QUOTES, 'UTF-8'); ?>"
                             class="w-full h-full object-cover group-hover:scale-105 transition">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <i class="fas fa-image text-4xl"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="p-3">
                    <h3 class="font-semibold text-gray-800 text-sm line-clamp-2 mb-1">
                        <?php echo htmlspecialchars($p['ten_san_pham'], ENT_QUOTES, 'UTF-8'); ?>
                    </h3>
                    <?php if ($p['ten_loai']): ?>
                        <p class="text-xs text-gray-500 mb-1"><?php echo htmlspecialchars($p['ten_loai'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>
                    <p class="text-red-500 font-bold"><?php echo number_format($p['gia_san_pham']); ?> VNĐ</p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- Phân trang -->
        <?php if ($totalPages > 1): ?> <!-- ẩn đi nếu kh có sản phẩm -->
        <div class="flex justify-center mt-8 gap-2">
            <?php if ($page > 1): ?>
                <a href="<?php echo BASE_URL; ?>/public/index.php?page=products&p=<?php echo $page-1; ?>&search=<?php echo urlencode($keyword); ?>&category=<?php echo $categoryId; ?>"
                   class="px-4 py-2 border rounded hover:bg-gray-100">&laquo; Trước</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="<?php echo BASE_URL; ?>/public/index.php?page=products&p=<?php echo $i; ?>&search=<?php echo urlencode($keyword); ?>&category=<?php echo $categoryId; ?>"
                   class="px-4 py-2 border rounded <?php echo $i == $page ? 'bg-red-500 text-white' : 'hover:bg-gray-100'; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="<?php echo BASE_URL; ?>/public/index.php?page=products&p=<?php echo $page+1; ?>&search=<?php echo urlencode($keyword); ?>&category=<?php echo $categoryId; ?>"
                   class="px-4 py-2 border rounded hover:bg-gray-100">Sau &raquo;</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>