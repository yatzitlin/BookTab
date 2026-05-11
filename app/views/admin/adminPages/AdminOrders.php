<?php
require_once dirname(__FILE__) . '/../../../models/OrderModel.php';
$orderModel = new OrderModel($dbConnection);

$p          = max(1, intval($_GET['p'] ?? 1));
$orders     = $orderModel->getAllOrders($p, 20);
$totalOrders = $orderModel->countOrders();
$totalPages = ceil($totalOrders / 20);
?>

<div class="main-content-inner">
    <div class="container-fluid mt-4">
        <h3 class="mb-4">Quản lý đơn hàng</h3>

        <?php if (empty($orders)): ?>
            <p class="text-muted">Chưa có đơn hàng nào.</p>
        <?php else: ?>
        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Khách hàng</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Cập nhật</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orders as $o): ?>
                        <?php
                        $badgeMap = [
                            'pending' => 'warning', 'confirmed' => 'info',
                            'shipping' => 'primary', 'delivered' => 'success', 'cancelled' => 'danger'
                        ];
                        $badge = $badgeMap[$o['trang_thai_don_hang']] ?? 'secondary';
                        ?>
                        <tr>
                            <td><?php echo $o['ma_don']; ?></td>
                            <td><?php echo $o['member_userid']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($o['ngay_dat'])); ?></td>
                            <td><?php echo number_format($o['tong_tien']); ?>đ</td>
                            <td><span class="badge bg-<?php echo $badge; ?>"><?php echo $o['trang_thai_don_hang']; ?></span></td>
                            <td>
                                <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?action=admin_order" class="d-flex gap-1">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <input type="hidden" name="ma_don" value="<?php echo $o['ma_don']; ?>">
                                    <select name="trang_thai" class="form-select form-select-sm" style="width:140px">
                                        <option value="pending" <?php echo $o['trang_thai_don_hang'] === 'pending' ? 'selected' : ''; ?>>Chờ xác nhận</option>
                                        <option value="confirmed" <?php echo $o['trang_thai_don_hang'] === 'confirmed' ? 'selected' : ''; ?>>Xác nhận</option>
                                        <option value="shipping" <?php echo $o['trang_thai_don_hang'] === 'shipping' ? 'selected' : ''; ?>>Đang giao</option>
                                        <option value="delivered" <?php echo $o['trang_thai_don_hang'] === 'delivered' ? 'selected' : ''; ?>>Đã giao</option>
                                        <option value="cancelled" <?php echo $o['trang_thai_don_hang'] === 'cancelled' ? 'selected' : ''; ?>>Huỷ</option>
                                    </select>
                                    <button class="btn btn-sm btn-primary">Lưu</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- Phân trang -->
        <?php if ($totalPages > 1): ?>
        <nav class="mt-3">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo $p <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=orders&p=<?php echo $p - 1; ?>">«</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo $i === $p ? 'active' : ''; ?>">
                    <a class="page-link" href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=orders&p=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?php echo $p >= $totalPages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?php echo BASE_URL; ?>/public/index.php?page=admin&admin_action=orders&p=<?php echo $p + 1; ?>">»</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>
