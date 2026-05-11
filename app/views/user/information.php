<?php if (!isset($user)) $user = []; ?>

<?php

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;

unset($_SESSION['success']);
unset($_SESSION['error']);

?>

<div class="max-w-7xl mx-auto p-6">

    <!-- HEADER -->
    <div class="mb-8">

        <h1 class="text-4xl font-bold text-gray-900">
            Cài đặt tài khoản
        </h1>

        <p class="text-gray-500 mt-2">
            Quản lý thông tin cá nhân và bảo mật tài khoản
        </p>

    </div>

    <!-- SUCCESS -->
    <?php if ($success): ?>

        <div class="mb-5 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl">
            <?= htmlspecialchars($success) ?>
        </div>

    <?php endif; ?>

    <!-- SERVER ERROR -->
    <?php if ($error): ?>

        <div class="server-error mb-5 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>

    <!-- JS ERROR -->
    <div id="js-error"
         class="hidden mb-5 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl">
    </div>

    <div class="grid grid-cols-12 gap-6">

        <!-- SIDEBAR -->
        <aside class="col-span-12 md:col-span-3">

            <div class="bg-white rounded-3xl shadow-sm border p-5 sticky top-6">

                <div class="flex flex-col items-center text-center mb-6">

                    <div class="w-24 h-24 rounded-full overflow-hidden bg-blue-100 shadow">

                        <?php if (!empty($user['avatar_url'])): ?>

                            <img src="/BOOKTAB/public/uploads/avatars/<?= htmlspecialchars($user['avatar_url']) ?>"
                                 class="w-full h-full object-cover">

                        <?php else: ?>

                            <div class="w-full h-full flex items-center justify-center text-3xl font-bold text-blue-600">
                                <?= strtoupper(substr($user['ten'] ?? 'U', 0, 1)) ?>
                            </div>

                        <?php endif; ?>

                    </div>

                    <h2 class="mt-4 font-semibold text-lg text-gray-800">
                        <?= htmlspecialchars(($user['ho_va_ten_dem'] ?? '') . ' ' . ($user['ten'] ?? '')) ?>
                    </h2>

                    <p class="text-sm text-gray-500">
                        @<?= htmlspecialchars($user['username'] ?? '') ?>
                    </p>

                </div>

                <nav class="space-y-2">

                    <a href="index.php?page=profile"
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-blue-50 text-blue-600 font-medium hover:bg-blue-100 transition">
                        👤 Hồ sơ cá nhân
                    </a>

                    <a href="index.php?page=change-password"
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-gray-700 hover:bg-gray-100 transition">
                        🔒 Đổi mật khẩu
                    </a>

                    <a href="index.php?action=logout"
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-red-500 hover:bg-red-50 transition">
                        🚪 Đăng xuất
                    </a>

                </nav>

            </div>

        </aside>

        <!-- MAIN -->
        <main class="col-span-12 md:col-span-9 space-y-6">

            <!-- PROFILE -->
            <div class="bg-white rounded-3xl shadow-sm border overflow-hidden">

                <div class="px-6 py-5 border-b bg-gray-50">

                    <h2 class="text-xl font-semibold text-gray-800">
                        Thông tin cá nhân
                    </h2>

                </div>

                <div class="p-6">

                    <!-- AVATAR -->
                    <div class="flex flex-col md:flex-row md:items-center gap-6 mb-8">

                        <div class="w-28 h-28 rounded-full overflow-hidden bg-blue-100 shadow">

                            <?php if (!empty($user['avatar_url'])): ?>

                                <img src="/BOOKTAB/public/uploads/avatars/<?= htmlspecialchars($user['avatar_url']) ?>"
                                     class="w-full h-full object-cover">

                            <?php else: ?>

                                <div class="w-full h-full flex items-center justify-center text-4xl font-bold text-blue-600">
                                    <?= strtoupper(substr($user['ten'] ?? 'U', 0, 1)) ?>
                                </div>

                            <?php endif; ?>

                        </div>

                        <form method="POST"
                              action="index.php?page=update-avatar"
                              enctype="multipart/form-data">

                            <input type="file"
                                   name="avatar"
                                   accept="image/*"
                                   class="block mb-3 text-sm">

                            <button class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition">
                                Cập nhật ảnh
                            </button>

                            <p class="text-xs text-gray-400 mt-2">
                                PNG, JPG tối đa 2MB
                            </p>

                        </form>

                    </div>

                    <!-- FORM -->
                    <form method="POST"
                          action="index.php?page=update-profile"
                          id="profileForm">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <div>

                                <label class="text-sm font-medium text-gray-600">
                                    Họ và tên đệm
                                </label>

                                <input type="text"
                                       name="ho_va_ten_dem"
                                       class="w-full mt-2 border rounded-2xl p-3 focus:ring-2 focus:ring-blue-200 outline-none"
                                       value="<?= htmlspecialchars($user['ho_va_ten_dem'] ?? '') ?>">

                            </div>

                            <div>

                                <label class="text-sm font-medium text-gray-600">
                                    Tên
                                </label>

                                <input type="text"
                                       name="ten"
                                       class="w-full mt-2 border rounded-2xl p-3 focus:ring-2 focus:ring-blue-200 outline-none"
                                       value="<?= htmlspecialchars($user['ten'] ?? '') ?>">

                            </div>

                            <div>

                                <label class="text-sm font-medium text-gray-600">
                                    Email
                                </label>

                                <input type="email"
                                       name="email"
                                       id="email"
                                       class="w-full mt-2 border rounded-2xl p-3 focus:ring-2 focus:ring-blue-200 outline-none"
                                       value="<?= htmlspecialchars($user['email'] ?? '') ?>">

                            </div>

                            <div>

                                <label class="text-sm font-medium text-gray-600">
                                    Số điện thoại
                                </label>

                                <input type="text"
                                       name="so_dien_thoai"
                                       id="phone"
                                       class="w-full mt-2 border rounded-2xl p-3 focus:ring-2 focus:ring-blue-200 outline-none"
                                       value="<?= htmlspecialchars($user['so_dien_thoai'] ?? '') ?>">

                            </div>

                            <div>

                                <label class="text-sm font-medium text-gray-600">
                                    Username
                                </label>

                                <input type="text"
                                       readonly
                                       class="w-full mt-2 border rounded-2xl p-3 bg-gray-100"
                                       value="<?= htmlspecialchars($user['username'] ?? '') ?>">

                            </div>

                            <div>

                                <label class="text-sm font-medium text-gray-600">
                                    Vai trò
                                </label>

                                <input type="text"
                                       readonly
                                       class="w-full mt-2 border rounded-2xl p-3 bg-gray-100"
                                       value="<?= htmlspecialchars($role ?? 'member') ?>">

                            </div>

                        </div>

                        <button type="submit"
                                class="mt-8 w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-2xl font-medium transition">

                            Lưu thay đổi

                        </button>

                    </form>

                </div>

            </div>

        </main>

    </div>

</div>

<!-- JAVASCRIPT VALIDATION -->
<script>

const profileForm =
    document.getElementById("profileForm");

profileForm.addEventListener("submit", function(e) {

    const email =
        document.getElementById("email")
        .value
        .trim();

    const phone =
        document.getElementById("phone")
        .value
        .trim();

    const jsError =
        document.getElementById("js-error");

    const serverError =
        document.querySelector(".server-error");

    let error = "";

    // EMAIL
    const emailRegex =
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // PHONE
    const phoneRegex =
        /^[0-9]{9,11}$/;

    if (!emailRegex.test(email)) {

        error = "Email không hợp lệ";

    } else if (!phoneRegex.test(phone)) {

        error = "Số điện thoại không hợp lệ";
    }

    // HIỂN THỊ LỖI
    if (error) {

        e.preventDefault();

        if (serverError) {
            serverError.style.display = "none";
        }

        jsError.classList.remove("hidden");

        jsError.innerText = error;

    } else {

        jsError.classList.add("hidden");
    }

});

</script>