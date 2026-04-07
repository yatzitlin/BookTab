<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-width=1.0">
    <title>MobileS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cpsRed: '#d70018',
                        cpsDarkRed: '#e03c51', // Màu nền nút footer
                        footerBg: '#f8f9fa',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white text-gray-800 font-sans flex flex-col min-h-screen">

    <header class="bg-gradient-to-r from-[#d70018] to-[#ff0055] py-2.5 shadow-md sticky top-0 z-50">
        <div class="max-w-[1200px] mx-auto flex items-center justify-between px-4 gap-4 text-white text-sm">
            <a href="/" class="flex items-center gap-1.5 font-bold tracking-tighter shrink-0">
                <span class="text-2xl mt-1">cellphone</span>
                <span class="border-2 border-white rounded-[4px] px-1.5 py-0.5 text-xl leading-none">S</span>
            </a>

            <button class="hidden lg:flex items-center gap-2 bg-white/20 hover:bg-white/30 px-3 py-2 rounded-xl transition shrink-0 cursor-pointer">
                <i class="fas fa-table-cells-large text-lg"></i>
                <span class="font-medium">Danh mục</span>
                <i class="fas fa-chevron-down text-[10px] ml-1"></i>
            </button>

            <button class="hidden lg:flex items-center gap-2 bg-white/20 hover:bg-white/30 px-3 py-2 rounded-xl transition shrink-0 cursor-pointer">
                <i class="fas fa-location-dot text-lg"></i>
                <span class="font-medium">Hồ Chí Minh</span>
                <i class="fas fa-chevron-down text-[10px] ml-1"></i>
            </button>

            <div class="flex-1 max-w-[500px]">
                <form action="/search" method="GET" class="relative flex items-center">
                    <i class="fas fa-search absolute left-3.5 text-gray-500 text-lg"></i>
                    <input type="text" name="q" class="w-full bg-white text-gray-800 rounded-xl pl-10 pr-4 py-2.5 focus:outline-none shadow-inner" placeholder="Bạn muốn mua gì hôm nay?">
                </form>
            </div>

            <a href="/cart" class="hidden md:flex items-center gap-2 hover:text-gray-200 transition shrink-0">
                <span class="font-medium">Giỏ hàng</span>
                <i class="fas fa-cart-shopping text-2xl relative">
                    <span class="absolute -top-1 -right-2 bg-yellow-400 text-black text-[10px] font-bold px-1.5 py-0.5 rounded-full">0</span>
                </i>
            </a>

            <a href="/login" class="flex items-center gap-2 bg-white/20 hover:bg-white/30 px-3 py-2.5 rounded-xl transition shrink-0">
                <span class="font-medium hidden sm:block">Đăng nhập</span>
                <i class="far fa-user-circle text-[22px]"></i>
            </a>
        </div>
    </header>

    <main class="flex-grow max-w-[1200px] mx-auto w-full px-4 py-6">
        <?php
            if (isset($contentView)) {
                echo $contentView; 
            }
        ?>
    </main>
    <footer class="bg-footerBg pt-8 pb-12 border-t border-gray-200 text-[13px] text-gray-700">
        <div class="max-w-[1200px] mx-auto px-4">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <div>
                    <h3 class="font-bold text-gray-900 mb-3 text-base">Tổng đài hỗ trợ miễn phí</h3>
                    <ul class="space-y-1.5 mb-5">
                        <li>Mua hàng - bảo hành <strong class="text-gray-900">1800.2097</strong> (7h30 - 22h00)</li>
                        <li>Khiếu nại <strong class="text-gray-900">1800.2063</strong> (8h00 - 21h30)</li>
                    </ul>

                    <h3 class="font-bold text-gray-900 mb-3 text-base">Phương thức thanh toán</h3>
                    <div class="flex flex-wrap gap-2 mb-5">
                        <span class="border border-gray-300 rounded px-2 py-1 text-xs bg-white font-bold">Apple Pay</span>
                        <span class="border border-gray-300 rounded px-2 py-1 text-xs bg-white text-blue-600 font-bold">VNPAY</span>
                        <span class="border border-gray-300 rounded px-2 py-1 text-xs bg-white text-pink-500 font-bold">MoMo</span>
                        <span class="border border-gray-300 rounded px-2 py-1 text-xs bg-white text-blue-500 font-bold">ZaloPay</span>
                    </div>

                    <h3 class="font-bold text-gray-900 mb-3 text-base">ĐĂNG KÝ NHẬN TIN KHUYẾN MÃI</h3>
                    <div class="bg-white p-3 rounded border border-red-100 mb-3 text-center">
                        <p class="text-cpsRed font-bold mb-1">Nhận ngay voucher 10%</p>
                        <p class="text-[11px] text-gray-500">Voucher sẽ được gửi sau 24h, chỉ áp dụng cho khách hàng mới</p>
                    </div>
                    <form class="space-y-2">
                        <input type="email" placeholder="Nhập email của bạn" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:border-red-500">
                        <input type="tel" placeholder="Nhập số điện thoại của bạn" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:border-red-500">
                        <label class="flex items-center gap-2 text-xs">
                            <input type="checkbox" class="accent-cpsRed" checked>
                            <span>Tôi đồng ý với điều khoản của MobileS</span>
                        </label>
                        <button class="w-full bg-[#e03c51] text-white font-bold py-2 rounded uppercase hover:bg-red-700 transition">Đăng ký ngay</button>
                    </form>
                </div>

                <div>
                    <h3 class="font-bold text-gray-900 mb-3 text-base">Thông tin về chính sách</h3>
                    <ul class="space-y-2.5">
                        <li><a href="#" class="hover:text-cpsRed">Mua hàng và thanh toán Online</a></li>
                        <li><a href="#" class="hover:text-cpsRed">Mua hàng trả góp Online</a></li>
                        <li><a href="#" class="hover:text-cpsRed">Mua hàng trả góp bằng thẻ tín dụng</a></li>
                        <li><a href="#" class="hover:text-cpsRed">Chính sách giao hàng</a></li>
                        <li><a href="#" class="hover:text-cpsRed">Chính sách đổi trả</a></li>
                        <li><a href="#" class="hover:text-cpsRed">Tra điểm Smember</a></li>
                        <li><a href="#" class="hover:text-cpsRed">Xem ưu đãi Smember</a></li>
                        <li><a href="#" class="hover:text-cpsRed">Tra thông tin bảo hành</a></li>
                        <li><a href="#" class="hover:text-cpsRed">Tra cứu hoá đơn điện tử</a></li>
                        <li><a href="#" class="hover:text-cpsRed">Thông tin hoá đơn mua hàng</a></li>
                        <li><a href="#" class="hover:text-cpsRed">Trung tâm bảo hành chính hãng</a></li>
                        <li><a href="#" class="hover:text-cpsRed">Quy định về việc sao lưu dữ liệu</a></li>
                        <li><a href="#" class="hover:text-cpsRed">Chính sách khui hộp sản phẩm Apple</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-gray-900 mb-3 text-base">Dịch vụ và thông tin khác</h3>
                    <ul class="space-y-2.5 mb-6">
                        <li><a href="#" class="hover:text-cpsRed">Khách hàng doanh nghiệp (B2B)</a></li>
                        <li><a href="#" class="hover:text-cpsRed">Ưu đãi thanh toán</a></li>
                        <li><a href="#" class="hover:text-cpsRed">Quy chế hoạt động</a></li>
                        <li><a href="#" class="hover:text-cpsRed">Chính sách bảo mật thông tin cá nhân</a></li>
                        <li><a href="#" class="hover:text-cpsRed">Chính sách Bảo hành</a></li>
                        <li><a href="#" class="hover:text-cpsRed">Liên hệ hợp tác kinh doanh</a></li>
                        <li><a href="#" class="hover:text-cpsRed">Tuyển dụng</a></li>
                        <li><a href="#" class="hover:text-cpsRed">Dịch vụ bảo hành mở rộng</a></li>
                    </ul>
                    <h3 class="font-bold text-gray-900 mb-2">Mua sắm dễ dàng - Ưu đãi ngập tràn cùng app MobileS</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-16 h-16 bg-gray-200 flex items-center justify-center rounded text-xs border border-gray-300">QR Code</div>
                        <div class="flex flex-col gap-1">
                            <span class="bg-black text-white px-3 py-1 rounded text-xs cursor-pointer"><i class="fab fa-google-play mr-1"></i> Google Play</span>
                            <span class="bg-black text-white px-3 py-1 rounded text-xs cursor-pointer"><i class="fab fa-apple mr-1"></i> App Store</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-gray-900 mb-3 text-base">Kết nối với MobileS</h3>
                    <div class="flex gap-3 mb-6 text-2xl">
                        <a href="#" class="text-red-600 hover:opacity-80"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="text-blue-600 hover:opacity-80"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-pink-600 hover:opacity-80"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-black hover:opacity-80"><i class="fab fa-tiktok"></i></a>
                        <a href="#" class="text-blue-400 hover:opacity-80"><i class="fas fa-comment-dots"></i></a>
                    </div>

                    <h3 class="font-bold text-gray-900 mb-3 text-base">Website thành viên</h3>
                    <ul class="space-y-4">
                        <li>
                            <p class="mb-1 text-gray-600">Hệ thống bảo hành và chăm sóc Điện thoại - Máy tính</p>
                            <span class="bg-red-600 text-white font-bold px-2 py-0.5 rounded text-sm">dienthoaivui</span>
                        </li>
                        <li>
                            <p class="mb-1 text-gray-600">Trung tâm bảo hành uỷ quyền Apple</p>
                            <span class="bg-blue-900 text-white font-bold px-2 py-0.5 rounded text-sm">careS</span>
                        </li>
                        <li>
                            <p class="mb-1 text-gray-600">Kênh thông tin giải trí công nghệ cho giới trẻ</p>
                            <span class="bg-red-500 text-white font-bold px-2 py-0.5 rounded text-sm">SChannel</span>
                        </li>
                        <li>
                            <p class="mb-1 text-gray-600">Trang thông tin công nghệ mới nhất</p>
                            <span class="bg-red-600 text-white font-bold px-2 py-0.5 rounded text-sm">Sforum.vn</span>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="border-t border-gray-200 pt-6 mt-6 pb-6 text-[11px] text-gray-500">
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-y-2 gap-x-4 mb-4">
                    <a href="#" class="hover:text-gray-800">iPhone Air</a>
                    <a href="#" class="hover:text-gray-800">Điện thoại</a>
                    <a href="#" class="hover:text-gray-800">Laptop</a>
                    <a href="#" class="hover:text-gray-800">Đồ gia dụng</a>
                    <a href="#" class="hover:text-gray-800">iPhone 17</a>
                    <a href="#" class="hover:text-gray-800">Điện thoại iPhone</a>
                    <a href="#" class="hover:text-gray-800">Laptop Acer</a>
                    <a href="#" class="hover:text-gray-800">Máy hút bụi gia đình</a>
                    <a href="#" class="hover:text-gray-800">iPhone 17 Pro</a>
                    <a href="#" class="hover:text-gray-800">Xiaomi</a>
                    <a href="#" class="hover:text-gray-800">Laptop Dell</a>
                    <a href="#" class="hover:text-gray-800">Build PC</a>
                    <a href="#" class="hover:text-gray-800">iPhone 17 Pro Max</a>
                    <a href="#" class="hover:text-gray-800">Điện thoại Samsung Galaxy</a>
                    <a href="#" class="hover:text-gray-800">Laptop HP</a>
                    <a href="#" class="hover:text-gray-800">Camera</a>
                    <a href="#" class="hover:text-gray-800">iPhone 15 Pro Max</a>
                    <a href="#" class="hover:text-gray-800">Điện thoại OPPO</a>
                    <a href="#" class="hover:text-gray-800">Tivi</a>
                    <a href="#" class="hover:text-gray-800">Trả góp</a>
                </div>
                <div class="text-center mt-6 text-[10px] leading-relaxed max-w-4xl mx-auto">
                    <p>Công ty TNHH Thương Mại và Dịch Vụ Kỹ Thuật MobileS - GPĐKKD: 0316172372 cấp tại Sở KH & ĐT TP. HCM. Địa chỉ văn phòng: Đại học Bách Khoa TP.HCM, Quận 10, Thành phố Hồ Chí Minh, Việt Nam. Điện thoại: 028.7108.9666.</p>
                </div>
            </div>
        </div>
    </footer>

    <a href="#" class="fixed bottom-6 right-6 bg-[#d70018] text-white px-4 py-2 rounded-full font-bold shadow-lg hover:bg-red-700 transition flex items-center gap-2 z-50 text-sm">
        Liên hệ <i class="fas fa-headset"></i>
    </a>

</body>
</html>