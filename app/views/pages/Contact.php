<?php $contact = $GLOBALS['contact_data'] ?? []; ?>
<div class="max-w-6xl mx-auto px-4 py-16">

    <!-- HEADER -->
    <header class="text-center mb-16">
        <div class="flex justify-center items-center gap-4 mb-2">
            <i class="fas fa-book-open text-3xl text-orange-600" aria-hidden="true"></i>

            <h1 class="text-4xl md:text-5xl font-bold brand-font text-slate-900 uppercase tracking-wide">
                Liên Hệ Với Chúng Tôi
            </h1>

            <i class="fas fa-comment-dots text-3xl text-orange-600" aria-hidden="true"></i>
        </div>

        <div class="h-1 w-24 bg-orange-500 mx-auto rounded-full"></div>
    </header>

    <!-- ALERT SUCCESS -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="mb-8 p-4 bg-green-100 border-l-4 border-green-600 text-green-700 rounded-lg"
             role="alert">
            <p class="font-bold">Thành công!</p>
            <p><?php echo htmlspecialchars($_SESSION['success']); ?></p>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- ALERT ERROR -->
    <?php if (!empty($_SESSION['errors'])): ?>
        <div class="mb-8 p-4 bg-red-100 border-l-4 border-red-600 text-red-700 rounded-lg"
             role="alert">

            <p class="font-bold mb-2">Lỗi!</p>

            <ul class="list-disc list-inside">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>

        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <!-- MAIN GRID -->
    <main class="grid grid-cols-1 lg:grid-cols-12 gap-12">

        <!-- LEFT: CONTACT INFO -->
        <section class="lg:col-span-5 bg-[#f3e9dc] p-8 md:p-12 rounded-3xl shadow-lg border border-orange-100"
                 aria-labelledby="contact-info-title">

            <h2 id="contact-info-title"
                class="text-2xl font-bold brand-font mb-8 border-b border-orange-200 pb-4 uppercase">
                Thông tin liên hệ
            </h2>

            <div class="space-y-8">

                <!-- ADDRESS -->
                <div class="flex gap-4">
                    <i class="fas fa-map-marker-alt text-orange-600 text-xl" aria-hidden="true"></i>

                    <div>
                        <h3 class="font-bold text-lg">Địa chỉ</h3>
                        <p class="text-gray-700">
                            <?php echo htmlspecialchars($contact['Address'] ?? 'Chưa cập nhật'); ?>
                        </p>
                    </div>
                </div>

                <!-- PHONE -->
                <div class="flex gap-4">
                    <i class="fas fa-phone-alt text-orange-600 text-xl" aria-hidden="true"></i>

                    <div>
                        <h3 class="font-bold text-lg">Hotline</h3>

                        <a href="tel:<?php echo htmlspecialchars($contact['PhoneNumber'] ?? ''); ?>"
                           class="text-xl font-semibold hover:text-orange-600 transition">

                            <?php echo htmlspecialchars($contact['PhoneNumber'] ?? 'Chưa cập nhật'); ?>
                        </a>

                        <p class="text-sm text-gray-500">Hỗ trợ 24/7</p>
                    </div>
                </div>

                <!-- EMAIL -->
                <div class="flex gap-4">
                    <i class="fas fa-envelope text-orange-600 text-xl" aria-hidden="true"></i>

                    <div>
                        <h3 class="font-bold text-lg">Email</h3>

                        <a href="mailto:<?php echo htmlspecialchars($contact['Email'] ?? ''); ?>"
                           class="text-gray-700 hover:text-orange-600 transition">

                            <?php echo htmlspecialchars($contact['Email'] ?? 'Chưa cập nhật'); ?>
                        </a>

                        <p class="text-sm text-green-600">Phản hồi trong 24h</p>
                    </div>
                </div>

                <!-- SOCIAL -->
                <div class="pt-6 border-t border-orange-200">
                    <h3 class="font-bold mb-4 uppercase">Theo dõi</h3>

                    <div class="flex gap-4 items-center">
                        <a href="#" class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center">
                            <i class="fab fa-facebook-f" aria-hidden="true"></i>
                        </a>

                        <a href="#" class="w-10 h-10 bg-pink-500 text-white rounded-full flex items-center justify-center">
                            <i class="fab fa-instagram" aria-hidden="true"></i>
                        </a>

                        <a href="#" class="w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center">
                            <i class="fab fa-youtube" aria-hidden="true"></i>
                        </a>

                        <span class="text-sm ml-2">@bookshop</span>
                    </div>
                </div>

            </div>
        </section>

        <!-- RIGHT: FORM -->
        <section class="lg:col-span-7 bg-white p-8 md:p-12 rounded-3xl shadow-xl border border-slate-100"
                 aria-labelledby="contact-form-title">

            <h2 id="contact-form-title"
                class="text-2xl font-bold brand-font mb-8 uppercase">
                Gửi tin nhắn
            </h2>

            <form action="<?php echo BASE_URL; ?>/public/index.php?page=contact-send"
                  method="POST"
                  class="space-y-6">

                <div>
                    <label class="sr-only" for="fullname">Họ và tên</label>
                    <input type="text"
                           id="fullname"
                           name="fullname"
                           placeholder="Họ và tên"
                           class="w-full px-5 py-4 bg-slate-50 border rounded-xl focus:ring-2 focus:ring-orange-500">
                </div>

                <div>
                    <label class="sr-only" for="email">Email</label>
                    <input type="email"
                           id="email"
                           name="email"
                           placeholder="Email *"
                           required
                           class="w-full px-5 py-4 bg-slate-50 border rounded-xl focus:ring-2 focus:ring-orange-500">
                </div>

                <div>
                    <label class="sr-only" for="topic">Chủ đề</label>
                    <select id="topic"
                            name="topic"
                            class="w-full px-5 py-4 bg-slate-50 border rounded-xl focus:ring-2 focus:ring-orange-500">

                        <option>Hỗ trợ đơn hàng</option>
                        <option>Tìm sách</option>
                        <option>Hợp tác</option>
                        <option>Góp ý</option>

                    </select>
                </div>

                <div>
                    <label class="sr-only" for="message">Nội dung</label>
                    <textarea id="message"
                              name="message"
                              rows="5"
                              placeholder="Nội dung..."
                              required
                              class="w-full px-5 py-4 bg-slate-50 border rounded-xl focus:ring-2 focus:ring-orange-500"></textarea>
                </div>

                <button type="submit"
                        class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-4 rounded-xl uppercase tracking-widest">
                    Gửi Yêu Cầu
                </button>

            </form>

        </section>

    </main>

    <!-- MAP -->
    <section class="mt-16 rounded-3xl overflow-hidden shadow-inner h-64 bg-slate-200 flex items-center justify-center border-4 border-white"
             aria-label="Google Maps">

        <p class="text-slate-500">
            <i class="fas fa-map-marked-alt mr-2" aria-hidden="true"></i>
            Google Maps
        </p>

    </section>

</div>