<div class="max-w-6xl mx-auto px-4 py-16">

    <!-- TIÊU ĐỀ -->
    <div class="text-center mb-16">
        <div class="flex justify-center items-center gap-4 mb-2">
            <i class="fas fa-book-open text-3xl text-orange-600"></i>
            <h1 class="text-4xl md:text-5xl font-bold brand-font text-slate-900 uppercase tracking-wide">
                Liên Hệ Với Chúng Tôi
            </h1>
            <i class="fas fa-comment-dots text-3xl text-orange-600"></i>
        </div>
        <div class="h-1 w-24 bg-orange-500 mx-auto rounded-full"></div>
    </div>

    <!-- THÔNG BÁO THÀNH CÔNG -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="mb-8 p-4 bg-green-100 border-l-4 border-green-600 text-green-700 rounded-lg flex items-center gap-3">
            <i class="fas fa-check-circle text-xl"></i>
            <div>
                <p class="font-bold">Thành công!</p>
                <p><?php echo $_SESSION['success']; ?></p>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- THÔNG BÁO LỖI -->
    <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
        <div class="mb-8 p-4 bg-red-100 border-l-4 border-red-600 text-red-700 rounded-lg">
            <p class="font-bold mb-2 flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                Lỗi!
            </p>
            <ul class="list-disc list-inside space-y-1">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

        <!-- THÔNG TIN LIÊN HỆ -->
        <div class="lg:col-span-5 bg-[#f3e9dc] p-8 md:p-12 rounded-3xl shadow-lg border border-orange-100">

            <h2 class="text-2xl font-bold brand-font mb-8 border-b border-orange-200 pb-4 uppercase">
                Thông tin liên hệ
            </h2>

            <div class="space-y-8">

                <div class="flex gap-4">
                    <div class="text-orange-600 text-xl"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <h4 class="font-bold text-lg">ĐỊA CHỈ:</h4>
                        <p>Trường Đại học Bách khoa - ĐHQG-HCM, cơ sở Dĩ An, Bình Dương</p>
                        <p class="text-sm text-orange-700 mt-1">
                            <i class="far fa-clock mr-1"></i> 8AM - 9PM
                        </p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="text-orange-600 text-xl"><i class="fas fa-phone-alt"></i></div>
                    <div>
                        <h4 class="font-bold text-lg">HOTLINE:</h4>
                        <p class="text-xl font-semibold">+84 (123) 456-789/p>
                        <p class="text-sm text-slate-500">Hỗ trợ 24/7</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="text-orange-600 text-xl"><i class="fas fa-envelope"></i></div>
                    <div>
                        <h4 class="font-bold text-lg">EMAIL:</h4>
                        <p>support@BookTab.com</p>
                        <p class="text-sm text-green-700 mt-1">Phản hồi trong 24h</p>
                    </div>
                </div>

                <div class="pt-6 border-t border-orange-200">
                    <h4 class="font-bold mb-4 uppercase">Theo dõi:</h4>
                    <div class="flex gap-4 items-center">
                        <a class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a class="w-10 h-10 bg-pink-500 text-white rounded-full flex items-center justify-center">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a class="w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <span class="text-sm ml-2">@bookshop</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- FORM LIÊN HỆ -->
        <div class="lg:col-span-7 bg-white p-8 md:p-12 rounded-3xl shadow-xl border border-slate-100">

            <h3 class="text-2xl font-bold brand-font mb-8 uppercase">
                Gửi tin nhắn
            </h3>

            <form action="<?php echo BASE_URL; ?>/public/index.php?page=contact-send" method="POST" class="space-y-7">

                <input type="text" name="fullname" placeholder="Họ và tên"
                    class="w-full px-5 py-4 bg-slate-50 border rounded-xl focus:ring-2 focus:ring-orange-500">

                <input type="email" name="email" placeholder="Email *" required
                    class="w-full px-5 py-4 bg-slate-50 border rounded-xl focus:ring-2 focus:ring-orange-500">

                <select name="topic"
                    class="w-full px-5 py-4 bg-slate-50 border rounded-xl focus:ring-2 focus:ring-orange-500">

                    <option>Hỗ trợ đơn hàng</option>
                    <option>Tìm sách</option>
                    <option>Hợp tác</option>
                    <option>Góp ý</option>

                </select>

                <textarea name="message" rows="4" placeholder="Nội dung..." required
                    class="w-full px-5 py-4 bg-slate-50 border rounded-xl focus:ring-2 focus:ring-orange-500"></textarea>

                <button type="submit"
                    class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-4 rounded-xl uppercase tracking-widest">
                    Gửi Yêu Cầu
                </button>

            </form>

        </div>

    </div>

    <!-- MAP -->
    <div class="mt-16 rounded-3xl overflow-hidden shadow-inner h-64 bg-slate-200 flex items-center justify-center border-4 border-white">
        <p class="text-slate-500">
            <i class="fas fa-map-marked-alt mr-2"></i>
            Google Maps
        </p>
    </div>

</div>