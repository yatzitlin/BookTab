<!-- HERO -->
<section class="relative h-[400px] bg-cover bg-center flex items-center"
    style="background-image: url('/assets/images/hero.jpg');">

    <div class="absolute inset-0 bg-black bg-opacity-40"></div>

    <div class="container mx-auto px-4 relative z-10 text-white">
        <h1 class="text-4xl md:text-5xl font-bold mb-4 uppercase leading-tight">
            Khám phá thế giới <br> qua những trang sách
        </h1>

        <a href="/products"
           class="bg-yellow-500 hover:bg-yellow-600 px-8 py-3 rounded shadow font-bold transition inline-block">
            MUA NGAY
        </a>
    </div>
</section>

<!-- GIỚI THIỆU -->
<section class="py-16 container mx-auto px-4">
    <div class="flex flex-col md:flex-row items-center gap-12">

        <div class="md:w-1/2">
            <h2 class="text-3xl font-bold mb-6 text-blue-900 uppercase">
                Chào mừng đến với Nhà Sách Tri Thức Việt
            </h2>

            <p class="text-gray-600 mb-4">
                Không gian tri thức đa dạng với hàng ngàn đầu sách chất lượng.
            </p>

            <p class="text-gray-600">
                Đồng hành cùng bạn trên hành trình học tập và phát triển bản thân.
            </p>
        </div>

        <div class="md:w-1/2">
            <img src="/assets/images/about.jpg"
                 alt="about"
                 class="rounded-lg shadow-xl">
        </div>

    </div>
</section>

<!-- SÁCH NỔI BẬT -->
<section class="bg-gray-100 py-16">
    <div class="container mx-auto px-4 text-center">

        <!-- HEADER + XEM TẤT CẢ -->
        <div class="flex justify-between items-center mb-12">
            <h2 class="text-3xl font-bold text-blue-900 uppercase">
                Sách nổi bật
            </h2>

            <a href="<?php echo BASE_URL; ?>/public/index.php?page=products"
               class="text-yellow-600 font-bold hover:underline">
                Xem tất cả →
            </a>
        </div>

        <!-- GRID SÁCH -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">

            <?php
            $books = [
                ["title" => "Đắc Nhân Tâm", "price" => "120.000đ", "img" => "/assets/images/book1.jpg"],
                ["title" => "Số Đỏ", "price" => "80.000đ", "img" => "/assets/images/book2.jpg"],
                ["title" => "Nhật Ký Trong Tù", "price" => "120.000đ", "img" => "/assets/images/book3.jpg"],
                ["title" => "Harry Potter", "price" => "180.000đ", "img" => "/assets/images/book4.jpg"],
            ];
            ?>

            <?php foreach ($books as $book): ?>
                <div class="bg-white p-4 rounded shadow hover:shadow-xl transition">

                    <!-- ẢNH LOCAL (bạn tự upload vào /assets/images/) -->
                    <img src="<?= $book['img'] ?>"
                         alt="<?= $book['title'] ?>"
                         class="h-64 mx-auto mb-4 object-cover">

                    <h3 class="font-bold mb-2">
                        <?= $book['title'] ?>
                    </h3>

                    <p class="text-red-600 font-bold mb-4">
                        <?= $book['price'] ?>
                    </p>

                    <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 w-full rounded font-bold uppercase text-sm">
                        Mua ngay
                    </button>

                </div>
            <?php endforeach; ?>

        </div>

    </div>
</section>