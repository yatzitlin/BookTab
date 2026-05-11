<h1 class="text-2xl font-bold mb-6">Giỏ hàng của bạn</h1>

<?php if (empty($cartItems)): ?>
    <div class="text-center py-16">
        <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
        <p class="text-gray-500 text-lg mb-4">Giỏ hàng trống</p>
        <a href="<?php echo BASE_URL; ?>/public/index.php?page=products"
           class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600">Mua sắm ngay</a>
    </div>
<?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="p-3">Sản phẩm</th>
                    <th class="p-3">Giá</th>
                    <th class="p-3">Số lượng</th>
                    <th class="p-3">Thành tiền</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($cartItems as $item): ?>
                <tr class="border-b <?php echo $item['is_active'] == 0 ? 'bg-red-50 opacity-70' : ''; ?>">
                    <td class="p-3 flex items-center gap-3">
                        <?php if (!empty($item['anh_chinh'])): ?>
                            <img src="<?php echo htmlspecialchars($item['anh_chinh'], ENT_QUOTES, 'UTF-8'); ?>"
                                 class="w-16 h-16 object-cover rounded">
                        <?php endif; ?>
                        <div>
                            <span class="font-semibold">
                                <?php echo htmlspecialchars($item['ten_san_pham'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                            <?php if ($item['is_active'] == 0): ?>
                                <p class="text-xs text-red-500 mt-1">Ngừng kinh doanh</p>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="p-3"><?php echo number_format($item['gia_san_pham']); ?> VNĐ</td>
                    <td class="p-3">
                        <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?action=update_cart"
                              class="flex items-center gap-1">
                            <input type="hidden" name="product_id" value="<?php echo $item['ma_san_pham']; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['so_luong']; ?>"
                                   min="1" max="99" class="w-16 border rounded px-2 py-1 text-center">
                            <button type="submit" class="text-blue-500 hover:text-blue-700">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </form>
                    </td>
                    <td class="p-3 font-bold">
                        <?php echo number_format($item['gia_san_pham'] * $item['so_luong']); ?> VNĐ
                    </td>
                    <td class="p-3">
                        <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?action=remove_cart">
                            <input type="hidden" name="product_id" value="<?php echo $item['ma_san_pham']; ?>">
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="flex justify-between items-center mt-6 p-4 bg-gray-50 rounded-lg">
        <p class="text-xl font-bold">Tổng cộng:
            <span class="text-red-500"><?php echo number_format($cartTotal); ?> VNĐ</span>
        </p>
        <a href="<?php echo BASE_URL; ?>/public/index.php?page=checkout"
           class="bg-red-500 text-white px-8 py-3 rounded-lg font-bold hover:bg-red-600">
            Đặt hàng <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>
<?php endif; ?>
