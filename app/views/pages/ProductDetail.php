<div class="flex flex-col md:flex-row gap-8">
    <!-- Ảnh sản phẩm -->
    <div class="w-full md:w-1/2">
        <?php if (!empty($images)): ?>
            <!-- Ảnh chính -->
            <img id="mainImage" src="<?php echo htmlspecialchars($images[0]['url_anh'], ENT_QUOTES, 'UTF-8'); ?>"
                 alt="" class="w-full rounded-lg border mb-4">
            <!-- Thumbnail gallery (chỉ hiện khi có nhiều hơn 1 ảnh) -->
            <?php if (count($images) > 1): ?>
            <div class="flex gap-2 overflow-x-auto">
                <?php foreach ($images as $img): ?>
                <img src="<?php echo htmlspecialchars($img['url_anh'], ENT_QUOTES, 'UTF-8'); ?>"
                     onclick="document.getElementById('mainImage').src=this.src"
                     class="w-20 h-20 object-cover border rounded cursor-pointer hover:border-red-500">
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        <?php else: ?>
            <!-- Placeholder khi không có ảnh -->
            <div class="w-full aspect-square bg-gray-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-image text-6xl text-gray-300"></i>
            </div>
        <?php endif; ?>
    </div>

    <!-- Thông tin sản phẩm -->
    <div class="w-full md:w-1/2">
        <h1 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($product['ten_san_pham'], ENT_QUOTES, 'UTF-8'); ?></h1>
        <?php if ($product['ten_loai']): ?>
            <p class="text-gray-500 mb-2">Danh mục: <?php echo htmlspecialchars($product['ten_loai'], ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <p class="text-3xl font-bold text-red-500 mb-4"><?php echo number_format($product['gia_san_pham']); ?> VNĐ</p>

        <!-- Rating -->
        <?php if ($ratingInfo['total_reviews'] > 0): ?>
        <div class="flex items-center gap-2 mb-4">
            <span class="text-yellow-500">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star<?php echo $i <= round($ratingInfo['avg_rating']) ? '' : '-half-alt'; ?>"></i>
                <?php endfor; ?>
            </span>
            <span class="text-gray-500">(<?php echo $ratingInfo['total_reviews']; ?> đánh giá)</span>
        </div>
        <?php endif; ?>

        <div class="mb-6 text-gray-700 leading-relaxed">
            <?php echo nl2br(htmlspecialchars($product['mo_ta'] ?? 'Chưa có mô tả', ENT_QUOTES, 'UTF-8')); ?>
        </div>

        <!-- Kiểm tra sản phẩm còn kinh doanh không -->
        <?php if ($product['is_active'] == 0): ?>
            <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
                <span class="font-semibold">Sản phẩm này đã ngừng kinh doanh.</span>
            </div>
        <?php else: ?>
        <!-- Form thêm giỏ hàng — chỉ hiện khi SP còn active -->
        <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?action=add_to_cart" class="flex items-center gap-4">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="product_id" value="<?php echo $product['ma_san_pham']; ?>">
            <input type="number" name="quantity" value="1" min="1" max="99" class="w-20 px-3 py-2 border rounded text-center">
            <button type="submit" class="bg-red-500 text-white px-8 py-3 rounded-lg font-bold hover:bg-red-600 transition">
                <i class="fas fa-cart-plus mr-2"></i> Thêm vào giỏ
            </button>
        </form>
        <?php endif; ?>
    </div>
</div>

<!-- Đánh giá sản phẩm -->
<div class="mt-12">
    <h2 class="text-xl font-bold mb-6">Đánh giá sản phẩm</h2>

    <!-- Form viết đánh giá (chỉ khi đã đăng nhập) -->
    <?php if (isset($_SESSION['userid'])): ?>
    <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?action=add_review" class="bg-gray-50 p-6 rounded-lg mb-8">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="hidden" name="product_id" value="<?php echo $product['ma_san_pham']; ?>">
        <div class="mb-4">
            <label class="block font-semibold mb-1">Điểm đánh giá</label>
            <select name="diem" class="border rounded px-3 py-2">
                <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                <option value="4">⭐⭐⭐⭐ (4)</option>
                <option value="3">⭐⭐⭐ (3)</option>
                <option value="2">⭐⭐ (2)</option>
                <option value="1">⭐ (1)</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Nội dung</label>
            <textarea name="noi_dung" rows="3" class="w-full border rounded px-3 py-2" placeholder="Viết đánh giá..."></textarea>
        </div>
        <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600">Gửi đánh giá</button>
    </form>
    <?php endif; ?>

    <!-- Danh sách đánh giá -->
    <?php if (empty($reviews)): ?>
        <p class="text-gray-500">Chưa có đánh giá nào.</p>
    <?php else: ?>
        <div class="space-y-4">
        <?php foreach ($reviews as $r): ?>
            <div class="border-b pb-4">
                <div class="flex items-center gap-2 mb-1">
                    <strong><?php echo htmlspecialchars($r['ho_va_ten_dem'] . ' ' . $r['ten'], ENT_QUOTES, 'UTF-8'); ?></strong>
                    <span class="text-yellow-500"><?php echo str_repeat('⭐', $r['diem']); ?></span>
                </div>
                <p class="text-gray-700"><?php echo htmlspecialchars($r['noi_dung'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
