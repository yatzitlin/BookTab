<h1 class="text-2xl font-bold mb-6">Đơn hàng của tôi</h1>



<?php if (empty($orders)): ?>
    <div class="text-center py-16">
        <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
        <p class="text-gray-500 text-lg mb-4">Bạn chưa có đơn hàng nào.</p>
        <a href="<?php echo BASE_URL; ?>/public/index.php?page=products"
           class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600">Mua sắm ngay</a>
    </div>
<?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="p-3">Mã đơn</th>
                    <th class="p-3">Ngày đặt</th>
                    <th class="p-3">Địa chỉ</th>
                    <th class="p-3">Tổng tiền</th>
                    <th class="p-3">Trạng thái</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $o): ?>
                <?php
                // Map trạng thái → màu badge
                $badgeClass = match($o['trang_thai_don_hang']) {
                    'pending'   => 'bg-yellow-100 text-yellow-700',
                    'confirmed' => 'bg-blue-100 text-blue-700',
                    'shipping'  => 'bg-indigo-100 text-indigo-700',
                    'delivered' => 'bg-green-100 text-green-700',
                    'cancelled' => 'bg-red-100 text-red-600',
                    default     => 'bg-gray-100 text-gray-600',
                };
                $statusLabel = match($o['trang_thai_don_hang']) {
                    'pending'   => 'Chờ xác nhận',
                    'confirmed' => 'Đã xác nhận',
                    'shipping'  => 'Đang giao',
                    'delivered' => 'Đã giao',
                    'cancelled' => 'Đã huỷ',
                    default     => $o['trang_thai_don_hang'],
                };
                ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3 font-bold">#<?php echo $o['ma_don']; ?></td>
                    <td class="p-3"><?php echo date('d/m/Y H:i', strtotime($o['ngay_dat'])); ?></td>
                    <td class="p-3 max-w-xs truncate"><?php echo htmlspecialchars($o['dia_chi_giao_hang'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="p-3 font-bold text-red-500"><?php echo number_format($o['tong_tien']); ?> VNĐ</td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold <?php echo $badgeClass; ?>">
                            <?php echo $statusLabel; ?>
                        </span>
                    </td>
                    <td class="p-3">
                        <a href="<?php echo BASE_URL; ?>/public/index.php?page=order_detail&id=<?php echo $o['ma_don']; ?>"
                           class="text-blue-500 hover:underline text-sm">Chi tiết →</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
