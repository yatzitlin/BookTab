<div class="flex items-center gap-4 mb-6">
    <a href="<?php echo BASE_URL; ?>/public/index.php?page=orders"
       class="text-blue-500 hover:underline"><i class="fas fa-arrow-left mr-1"></i> Đơn hàng của tôi</a>
    <h1 class="text-2xl font-bold">Chi tiết đơn #<?php echo $order['ma_don']; ?></h1>
</div>

<!-- Thông tin đơn hàng -->
<div class="grid md:grid-cols-2 gap-6 mb-8">
    <div class="bg-gray-50 p-4 rounded-lg">
        <h3 class="font-bold mb-3">Thông tin giao hàng</h3>
        <p class="text-gray-700 mb-1"><strong>Địa chỉ:</strong>
            <?php echo htmlspecialchars($order['dia_chi_giao_hang'], ENT_QUOTES, 'UTF-8'); ?>
        </p>
        <p class="text-gray-700 mb-1"><strong>Phương thức:</strong>
            <?php echo htmlspecialchars($order['phuong_thuc_thanh_toan'], ENT_QUOTES, 'UTF-8'); ?>
        </p>
        <p class="text-gray-700"><strong>Ngày đặt:</strong>
            <?php echo date('d/m/Y H:i', strtotime($order['ngay_dat'])); ?>
        </p>
    </div>
    <div class="bg-gray-50 p-4 rounded-lg">
        <h3 class="font-bold mb-3">Trạng thái đơn hàng</h3>
        <?php
        $steps = ['pending' => 1, 'confirmed' => 2, 'shipping' => 3, 'delivered' => 4];
        $currentStep = $steps[$order['trang_thai_don_hang']] ?? 0;
        $labels = ['Chờ xác nhận', 'Đã xác nhận', 'Đang giao', 'Đã giao'];
        ?>
        <?php if ($order['trang_thai_don_hang'] === 'cancelled'): ?>
            <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full font-semibold">Đã huỷ</span>
        <?php else: ?>
        <div class="flex items-center gap-1 flex-wrap">
            <?php for ($s = 1; $s <= 4; $s++): ?>
                <div class="flex items-center gap-1">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold
                        <?php echo $s <= $currentStep ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-500'; ?>">
                        <?php echo $s; ?>
                    </div>
                    <span class="text-xs <?php echo $s === $currentStep ? 'text-red-500 font-bold' : 'text-gray-500'; ?>">
                        <?php echo $labels[$s-1]; ?>
                    </span>
                    <?php if ($s < 4): ?><span class="text-gray-300">→</span><?php endif; ?>
                </div>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Danh sách sách trong đơn -->
<h3 class="font-bold text-lg mb-3">Sản phẩm đã đặt</h3>
<table class="w-full border-collapse mb-6">
    <thead>
        <tr class="bg-gray-100 text-left">
            <th class="p-3">Tên sách</th>
            <th class="p-3">Đơn giá</th>
            <th class="p-3">Số lượng</th>
            <th class="p-3">Thành tiền</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($orderDetails as $d): ?>
        <tr class="border-b">
            <td class="p-3"><?php echo htmlspecialchars($d['ten_san_pham'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td class="p-3"><?php echo number_format($d['gia']); ?> VNĐ</td>
            <td class="p-3"><?php echo $d['so_luong']; ?></td>
            <td class="p-3 font-bold"><?php echo number_format($d['gia'] * $d['so_luong']); ?> VNĐ</td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="text-right text-xl font-bold">
    Tổng cộng: <span class="text-red-500"><?php echo number_format($order['tong_tien']); ?> VNĐ</span>
</div>
