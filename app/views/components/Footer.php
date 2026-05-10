<?php
require_once __DIR__ . '/../../models/ThongTinModel.php';
$thongTinModel = new ThongTinModel($dbConnection);
$footerData = $thongTinModel->getMultiByLoai([
    'intro', 'name', 'quick_links', 'support_links',
    'address', 'phone', 'email', 'copyright'
]);

// Fetch link items for link-type records
$footerLinks = [];
foreach (['quick_links', 'support_links'] as $key) {
    if (isset($footerData[$key]) && $footerData[$key]['type'] === 'link') {
        $footerLinks[$key] = $thongTinModel->getChiTietByMaThongTin($footerData[$key]['ma_thong_tin']);
    }
}

// Fetch text content for text-type records
$footerText = [];
foreach (['intro', 'name', 'address', 'phone', 'email', 'copyright'] as $key) {
    $footerText[$key] = $thongTinModel->getFirstNoiDung($key);
}

function renderFooterLinks($items) {
    if (empty($items)) return '';
    $html = '<ul class="space-y-2 text-sm">';
    foreach ($items as $item) {
        $text = htmlspecialchars($item['noi_dung'] ?? '', ENT_QUOTES, 'UTF-8');
        $url = htmlspecialchars($item['url'] ?? '#', ENT_QUOTES, 'UTF-8');
        $html .= '<li><a href="' . $url . '" class="hover:text-red-500 transition">' . $text . '</a></li>';
    }
    $html .= '</ul>';
    return $html;
}
?>

<footer class="bg-gray-900 text-gray-200 py-16">
    <div class="max-w-screen-2xl mx-auto px-8 lg:px-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <!-- About -->
            <div>
                <h4 class="text-white text-lg font-bold mb-4">Về <?php echo htmlspecialchars($footerText['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h4>
                <p class="text-sm leading-relaxed"><?php echo htmlspecialchars($footerText['intro'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
            </div>

            <!-- Links -->
            <div>
                <h4 class="text-white text-lg font-bold mb-4">Liên kết nhanh</h4>
                <?php echo renderFooterLinks($footerLinks['quick_links'] ?? []); ?>
            </div>

            <!-- Support -->
            <div>
                <h4 class="text-white text-lg font-bold mb-4">Hỗ trợ khách hàng</h4>
                <?php echo renderFooterLinks($footerLinks['support_links'] ?? []); ?>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="text-white text-lg font-bold mb-4">Thông tin liên hệ</h4>
                <ul class="space-y-3 text-sm">
                    <?php if (!empty($footerText['address'])): ?>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-map-marker-alt mt-1 text-red-500"></i>
                            <span><?php echo htmlspecialchars($footerText['address'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($footerText['phone'])): ?>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-phone-alt text-red-500"></i>
                            <span><?php echo htmlspecialchars($footerText['phone'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($footerText['email'])): ?>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-envelope text-red-500"></i>
                            <span><?php echo htmlspecialchars($footerText['email'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="mt-6">
                    <p class="text-white text-sm font-semibold mb-2">Nhận tin tức mới nhất</p>
                    <form class="flex gap-2">
                        <input type="email" placeholder="Email của bạn" class="flex-1 px-3 py-2 bg-gray-800 text-white text-sm rounded border border-gray-700 focus:border-red-500 focus:outline-none">
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white font-semibold text-sm rounded hover:bg-red-600 transition">Đăng ký</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-700 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-400">
            <div>
                <p><?php echo htmlspecialchars($footerText['copyright'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <div class="flex gap-6">
                <a href="#" class="hover:text-red-500 transition">Chính sách bảo mật</a>
                <a href="#" class="hover:text-red-500 transition">Điều khoản sử dụng</a>
                <a href="#" class="hover:text-red-500 transition">Sitemap</a>
            </div>
        </div>
    </div>
</footer>
