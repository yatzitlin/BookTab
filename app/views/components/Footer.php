<footer class="bg-gray-900 text-gray-200 px-16 py-16 mt-20">
    <div class=\"max-w-6xl mx-auto px-4 lg:px-8\">
        <!-- Footer Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <!-- About -->
            <div>
                <h4 class="text-white text-lg font-bold mb-4">Về BookTab</h4>
                <p class="text-gray-400 text-sm mb-4">BookTab là nền tảng bán sách trực tuyến hàng đầu, cung cấp hàng triệu đầu sách từ các tác giả nổi tiếng toàn thế giới.</p>
                <div class="flex gap-3">
                    <a href="#" class="inline-flex items-center justify-center w-10 h-10 bg-red-500 bg-opacity-20 rounded-full text-red-500 hover:bg-red-500 hover:text-white transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="inline-flex items-center justify-center w-10 h-10 bg-red-500 bg-opacity-20 rounded-full text-red-500 hover:bg-red-500 hover:text-white transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="inline-flex items-center justify-center w-10 h-10 bg-red-500 bg-opacity-20 rounded-full text-red-500 hover:bg-red-500 hover:text-white transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="inline-flex items-center justify-center w-10 h-10 bg-red-500 bg-opacity-20 rounded-full text-red-500 hover:bg-red-500 hover:text-white transition">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>

            <!-- Links -->
            <div>
                <h4 class="text-white text-lg font-bold mb-4">Liên kết nhanh</h4>
                <ul class="space-y-2">
                    <li><a href="<?php echo BASE_URL; ?>/public/index.php?page=home" class="text-gray-400 hover:text-red-500 text-sm transition">Trang chủ</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/public/index.php?page=products" class="text-gray-400 hover:text-red-500 text-sm transition">Cửa hàng</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/public/index.php?page=news" class="text-gray-400 hover:text-red-500 text-sm transition">Tin tức</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/public/index.php?page=qna" class="text-gray-400 hover:text-red-500 text-sm transition">Hỏi/Đáp</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/public/index.php?page=contact" class="text-gray-400 hover:text-red-500 text-sm transition">Liên hệ</a></li>
                </ul>
            </div>

            <!-- Customer Support -->
            <div>
                <h4 class="text-white text-lg font-bold mb-4">Hỗ trợ khách hàng</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 hover:text-red-500 text-sm transition">Chính sách bảo mật</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-red-500 text-sm transition">Điều khoản dịch vụ</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-red-500 text-sm transition">Hướng dẫn mua hàng</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-red-500 text-sm transition">Chính sách hoàn trả</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-red-500 text-sm transition">FAQ</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h4 class="text-white text-lg font-bold mb-4">Thông tin liên hệ</h4>
                <div class="space-y-3 mb-4">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-map-marker-alt text-red-500 mt-1 flex-shrink-0"></i>
                        <p class="text-gray-400 text-sm">Trường Đại học Bách khoa - ĐHQG-HCM, cơ sở Dĩ An, Bình Dương</p>
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="fas fa-phone text-red-500 mt-1 flex-shrink-0"></i>
                        <a href="tel:+84123456789" class="text-gray-400 hover:text-red-500 text-sm transition\">+84 (123) 456-789</a>
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="fas fa-envelope text-red-500 mt-1 flex-shrink-0"></i>
                        <a href="mailto:support@BookTab.com" class="text-gray-400 hover:text-red-500 text-sm transition">support@BookTab.com</a>
                    </div>
                </div>
                
                <!-- Newsletter -->
                <div>
                    <p class="text-white text-sm font-semibold mb-2">Nhận tin tức mới nhất</p>
                    <form class="flex gap-2">
                        <input type="email" placeholder="Email của bạn" class="flex-1 px-3 py-2 bg-gray-800 text-white text-sm rounded border border-gray-700 focus:border-red-500 focus:outline-none">
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white font-semibold text-sm rounded hover:bg-red-600 transition">Đăng ký</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-700 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-400">
            <p>&copy; 2026 BookTab.</p>
            <div class="flex gap-6">
                <a href="#" class="hover:text-red-500 transition">Chính sách bảo mật</a>
                <a href="#" class="hover:text-red-500 transition">Điều khoản sử dụng</a>
                <a href="#" class="hover:text-red-500 transition">Sitemap</a>
            </div>
        </div>
    </div>
</footer>
