<h1 class="text-2xl font-bold mb-6">Đặt hàng</h1>
<div class="flex flex-col md:flex-row gap-8">
    <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?action=checkout" class="flex-1">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <div class="mb-4">
            <label class="block font-semibold mb-1">
                Địa chỉ giao hàng <span class="text-red-500">*</span>
            </label>
            <textarea name="dia_chi" rows="3" required
                      class="w-full border rounded px-3 py-2 focus:border-red-500 focus:outline-none"
                      placeholder="Nhập địa chỉ giao hàng..."></textarea>
            <?php if (isset($_GET['error']) && $_GET['error'] === 'address'): ?>
                <p class="text-red-500 text-sm mt-1">Vui lòng nhập địa chỉ giao hàng.</p>
            <?php endif; ?>
        </div>

        <div class="mb-6">
            <label class="block font-semibold mb-1">Phương thức thanh toán</label>
            <select name="phuong_thuc" class="w-full border rounded px-3 py-2">
                <option value="COD">Thanh toán khi nhận hàng (COD)</option>
                <option value="banking">Chuyển khoản ngân hàng</option>
            </select>
        </div>

        <button type="submit" class="w-full bg-red-500 text-white py-3 rounded-lg font-bold hover:bg-red-600 text-lg">
            <i class="fas fa-check mr-2"></i> Xác nhận đặt hàng
        </button>
    </form>

    <!-- Tóm tắt đơn hàng -->
    <div class="w-full md:w-80 bg-gray-50 p-6 rounded-lg h-fit">
        <h3 class="font-bold text-lg mb-4">Đơn hàng của bạn</h3>
        <?php foreach ($cartItems as $item): ?>
        <div class="flex justify-between text-sm mb-2">
            <span><?php echo htmlspecialchars($item['ten_san_pham'], ENT_QUOTES, 'UTF-8'); ?>
                  x<?php echo $item['so_luong']; ?></span>
            <span><?php echo number_format($item['gia_san_pham'] * $item['so_luong']); ?>đ</span>
        </div>
        <?php endforeach; ?>
        <hr class="my-3">
        <div class="flex justify-between font-bold text-lg">
            <span>Tổng:</span>
            <span class="text-red-500"><?php echo number_format($cartTotal); ?> VNĐ</span>
        </div>
    </div>
</div>
