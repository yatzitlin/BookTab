<?php if (!isset($user)) $user = []; ?>

<div class="max-w-7xl mx-auto p-6">

    <!-- HEADER -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">Cài đặt tài khoản</h1>
        <p class="text-gray-500 mt-2">Quản lý thông tin cá nhân và bảo mật tài khoản</p>
    </div>

    <!-- ALERT -->
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-12 gap-6">

        <!-- SIDEBAR -->
        <aside class="col-span-12 md:col-span-3">

            <div class="bg-white rounded-2xl shadow-sm border p-4 sticky top-6">

                <div class="text-sm font-semibold text-gray-500 mb-3">MENU</div>

                <nav class="space-y-2">

                    <a href="index.php?page=account-information"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-50 text-blue-600 font-medium hover:bg-blue-100 transition">
                        👤 Thông tin cá nhân
                    </a>

                    <a href="index.php?page=change-password"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-100 transition">
                        🔒 Đổi mật khẩu
                    </a>

                    <a href="index.php?action=logout"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 transition">
                        🚪 Đăng xuất
                    </a>

                </nav>

            </div>

        </aside>

        <!-- MAIN -->
        <main class="col-span-12 md:col-span-9 space-y-6">

            <!-- AVATAR CARD -->
            <div class="bg-white rounded-2xl shadow-sm border p-6">

                <h2 class="text-lg font-semibold mb-5">Ảnh đại diện</h2>

                <div class="flex items-center gap-6">

                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center overflow-hidden shadow">

                        <?php if (!empty($user['avatar_url'])): ?>
                            <img src="/BOOKTAB/public/uploads/avatars/<?= htmlspecialchars($user['avatar_url']) ?>"
                                class="w-full h-full object-cover">
                        <?php else: ?>
                            <span class="text-2xl font-bold text-blue-600">
                                <?= strtoupper(substr($user['ten'] ?? 'U', 0, 1)) ?>
                            </span>
                        <?php endif; ?>

                    </div>

                    <form method="POST"
                          action="index.php?page=update-avatar"
                          enctype="multipart/form-data">

                        <input type="file"
                               name="avatar"
                               accept="image/*"
                               class="block text-sm mb-2">

                        <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition">
                            Cập nhật ảnh
                        </button>

                        <p class="text-xs text-gray-400 mt-1">PNG, JPG tối đa 2MB</p>

                    </form>

                </div>

            </div>

            <!-- PROFILE CARD -->
            <form method="POST"
                  action="index.php?page=update-profile">

                <div class="bg-white rounded-2xl shadow-sm border p-6">

                    <h2 class="text-lg font-semibold mb-5">Thông tin cá nhân</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>
                            <label class="text-sm text-gray-600">Họ và tên đệm</label>
                            <input name="ho_va_ten_dem"
                                   class="w-full mt-1 border rounded-xl p-3 focus:ring-2 focus:ring-blue-200"
                                   value="<?= htmlspecialchars($user['ho_va_ten_dem'] ?? '') ?>">
                        </div>

                        <div>
                            <label class="text-sm text-gray-600">Tên</label>
                            <input name="ten"
                                   class="w-full mt-1 border rounded-xl p-3 focus:ring-2 focus:ring-blue-200"
                                   value="<?= htmlspecialchars($user['ten'] ?? '') ?>">
                        </div>

                        <div>
                            <label class="text-sm text-gray-600">Số điện thoại</label>
                            <input name="so_dien_thoai"
                                   class="w-full mt-1 border rounded-xl p-3 focus:ring-2 focus:ring-blue-200"
                                   value="<?= htmlspecialchars($user['so_dien_thoai'] ?? '') ?>">
                        </div>

                        <div>
                            <label class="text-sm text-gray-600">Username</label>
                            <input readonly
                                   class="w-full mt-1 border rounded-xl p-3 bg-gray-100"
                                   value="<?= htmlspecialchars($user['username'] ?? '') ?>">
                        </div>

                    </div>

                    <button class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl transition">
                        Lưu thay đổi
                    </button>

                </div>

            </form>

            <!-- INFO CARD -->
            <div class="bg-white rounded-2xl shadow-sm border p-6">

                <h3 class="text-lg font-semibold mb-4">Thông tin tài khoản</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

                    <div class="p-4 bg-gray-50 rounded-xl">
                        <p class="text-gray-500">Vai trò</p>
                        <p class="font-semibold text-gray-800">
                            <?= $role ?? 'member' ?>
                        </p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-xl">
                        <p class="text-gray-500">Trạng thái</p>
                        <p class="font-semibold text-green-600">
                            <?= $user['trang_thai'] ?? '' ?>
                        </p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-xl">
                        <p class="text-gray-500">Ngày tạo</p>
                        <p class="font-semibold text-gray-800">
                            <?= $user['ngay_tao'] ?? '' ?>
                        </p>
                    </div>

                </div>

            </div>

        </main>

    </div>
</div>