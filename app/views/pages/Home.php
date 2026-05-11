<!-- HERO SLIDER -->
<section class="relative overflow-hidden h-[550px] rounded-2xl mx-4 mt-6">

    <div id="slider" class="flex h-full transition-transform duration-700 ease-in-out">

        <!-- SLIDE 1 -->
        <div class="min-w-full h-full relative bg-cover bg-center"
            style="background-image: url('<?php echo BASE_URL; ?>/public/admin_assets/home/trangsach.jpg');">

            <div class="absolute inset-0 bg-black/40"></div>

            <!-- TEXT bottom-left -->
            <div class="absolute bottom-10 left-10 z-10 text-white max-w-xl">
                <h1 class="text-4xl md:text-5xl font-bold uppercase leading-tight mb-4">
                    Khám phá thế giới <br> qua từng trang sách
                </h1>

                <a href="<?php echo BASE_URL; ?>/public/index.php?page=products"
                   class="bg-yellow-500 hover:bg-yellow-600 px-6 py-3 rounded font-bold transition inline-block">
                    MUA NGAY
                </a>
            </div>
        </div>

        <!-- SLIDE 2 -->
        <div class="min-w-full h-full relative bg-cover bg-center"
            style="background-image: url('<?php echo BASE_URL; ?>/public/admin_assets/home/slide.jpg');">

            <div class="absolute inset-0 bg-black/40"></div>

            <div class="absolute bottom-10 left-10 z-10 text-white max-w-xl">
                <h1 class="text-4xl md:text-5xl font-bold uppercase leading-tight mb-2">
                    Sách mở ra thế giới bạn chưa từng thấy
                </h1>

                <p class="text-lg text-gray-200">
                    Mỗi trang sách là một hành trình mới
                </p>
            </div>
        </div>

        <!-- SLIDE 3 -->
        <div class="min-w-full h-full relative bg-cover bg-center"
            style="background-image: url('<?php echo BASE_URL; ?>/public/admin_assets/home/docsach.jpg');">

            <div class="absolute inset-0 bg-black/40"></div>

            <div class="absolute bottom-10 left-10 z-10 text-white max-w-xl">
                <h1 class="text-4xl md:text-5xl font-bold uppercase leading-tight mb-2">
                    Đọc hôm nay – thay đổi ngày mai
                </h1>

                <p class="text-lg text-gray-200">
                    Thói quen nhỏ tạo nên tri thức lớn
                </p>
            </div>
        </div>

    </div>

    <!-- PREV -->
    <button onclick="prevSlide()"
        class="absolute left-4 top-1/2 -translate-y-1/2 
               bg-black/50 hover:bg-black/70 text-white 
               w-11 h-11 rounded-full flex items-center justify-center">
        ‹
    </button>

    <!-- NEXT -->
    <button onclick="nextSlide()"
        class="absolute right-4 top-1/2 -translate-y-1/2 
               bg-black/50 hover:bg-black/70 text-white 
               w-11 h-11 rounded-full flex items-center justify-center">
        ›
    </button>

</section>

<!-- GIỚI THIỆU -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4 flex flex-col md:flex-row items-center gap-14">

        <!-- TEXT -->
        <div class="md:w-1/2">

            <h2 class="text-4xl font-bold mb-6 text-blue-900 uppercase leading-tight">
                Hành trình tri thức bắt đầu từ những trang sách
            </h2>

            <p class="text-gray-600 mb-4 leading-relaxed">
                Nhà Sách Tri Thức Việt là không gian dành cho những người yêu sách, nơi bạn có thể tìm thấy hàng ngàn đầu sách thuộc nhiều lĩnh vực: kỹ năng sống, kinh doanh, văn học, thiếu nhi và phát triển bản thân.
            </p>

            <p class="text-gray-600 mb-6 leading-relaxed">
                Chúng tôi tin rằng mỗi cuốn sách là một người thầy, giúp bạn mở rộng tư duy, nâng cao kiến thức và thay đổi cuộc sống theo hướng tích cực hơn.
            </p>

            <!-- STATS -->
            <div class="flex gap-8">

                <div>
                    <p class="text-2xl font-bold text-yellow-500">10.000+</p>
                    <p class="text-gray-500 text-sm">Đầu sách</p>
                </div>

                <div>
                    <p class="text-2xl font-bold text-yellow-500">5.000+</p>
                    <p class="text-gray-500 text-sm">Khách hàng</p>
                </div>

                <div>
                    <p class="text-2xl font-bold text-yellow-500">24/7</p>
                    <p class="text-gray-500 text-sm">Hỗ trợ</p>
                </div>

            </div>

        </div>

        <!-- IMAGE -->
        <div class="md:w-1/2 relative">
            <img src="<?php echo BASE_URL; ?>/public/admin_assets/home/store.jpg"
                 alt="sach-van-hoc"
                 class="rounded-2xl shadow-2xl w-full object-cover">
        </div>

    </div>
</section>

<!-- SÁCH NỔI BẬT -->
<section class="bg-gray-100 py-16">
    <div class="container mx-auto px-4 text-center">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-12">

            <h2 class="text-3xl font-bold text-blue-900 uppercase">
                Sách nổi bật
            </h2>

            <a href="<?php echo BASE_URL; ?>/public/index.php?page=products"
               class="text-yellow-600 font-bold hover:underline">
                Xem tất cả →
            </a>

        </div>

        <!-- GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">

            <?php foreach ($featuredProducts as $book): ?>

                <div class="bg-white p-4 rounded shadow hover:shadow-2xl transition duration-300">

                    <!-- ẢNH -->
                    <img src="<?= htmlspecialchars($book['anh_chinh'], ENT_QUOTES, 'UTF-8') ?>"
                         alt="<?= htmlspecialchars($book['ten_san_pham']) ?>"
                         class="h-64 w-full object-cover rounded mb-4">

                    <!-- TÊN -->
                    <h3 class="font-bold text-lg mb-2 line-clamp-2">
                        <?= htmlspecialchars($book['ten_san_pham']) ?>
                    </h3>

                    <!-- GIÁ -->
                    <p class="text-red-600 font-bold text-xl mb-4">
                        <?= number_format($book['gia_san_pham'], 0, ',', '.') ?>đ
                    </p>

                    <!-- BUTTON -->
                    <a href="<?php echo BASE_URL; ?>/public/index.php?page=product_detail&id=<?= $book['ma_san_pham'] ?>"
                       class="block bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded font-bold uppercase text-sm transition">

                        Mua ngay

                    </a>

                </div>

            <?php endforeach; ?>

        </div>

    </div>
</section>