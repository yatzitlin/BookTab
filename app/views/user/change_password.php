<?php

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;

unset($_SESSION['success']);
unset($_SESSION['error']);

?>

<div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded-2xl shadow border">

    <!-- TITLE -->
    <div class="mb-6">

        <h1 class="text-3xl font-bold text-gray-800">
            Đổi mật khẩu
        </h1>

        <p class="text-gray-500 mt-2">
            Cập nhật mật khẩu mới để bảo mật tài khoản
        </p>

    </div>

    <!-- SUCCESS -->
    <?php if ($success): ?>

        <div class="mb-5 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
            <?= htmlspecialchars($success) ?>
        </div>

    <?php endif; ?>

    <!-- SERVER ERROR -->
    <?php if ($error): ?>

        <div class="server-error mb-5 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>

    <!-- JS ERROR -->
    <div id="js-error"
         class="hidden mb-5 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
    </div>

    <!-- FORM -->
    <form method="POST"
          action="index.php?page=change-password"
          id="changePassForm"
          class="space-y-5">

        <!-- OLD PASSWORD -->
        <div>

            <label class="block text-sm font-medium text-gray-700 mb-2">
                Mật khẩu hiện tại
            </label>

            <input type="password"
                   name="old_password"
                   class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-red-200 outline-none">

        </div>

        <!-- NEW PASSWORD -->
        <div>

            <label class="block text-sm font-medium text-gray-700 mb-2">
                Mật khẩu mới
            </label>

            <input type="password"
                   name="new_password"
                   class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-red-200 outline-none">

        </div>

        <!-- CONFIRM PASSWORD -->
        <div>

            <label class="block text-sm font-medium text-gray-700 mb-2">
                Xác nhận mật khẩu
            </label>

            <input type="password"
                   name="confirm_password"
                   class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-red-200 outline-none">

        </div>

        <!-- BUTTON -->
        <button type="submit"
                class="w-full bg-red-500 hover:bg-red-600 text-white py-3 rounded-xl font-semibold transition">

            Đổi mật khẩu

        </button>

    </form>

    <!-- BACK -->
    <a href="index.php?page=profile"
       class="block text-center mt-5 text-blue-500 hover:text-blue-700 transition">

        ← Quay lại trang cá nhân

    </a>

</div>

<!-- ================= JAVASCRIPT VALIDATION ================= -->
<script>

document
.getElementById("changePassForm")
.addEventListener("submit", function(e) {

    const oldPass =
        document.querySelector("[name='old_password']")
        .value
        .trim();

    const newPass =
        document.querySelector("[name='new_password']")
        .value
        .trim();

    const confirmPass =
        document.querySelector("[name='confirm_password']")
        .value
        .trim();

    const jsError =
        document.getElementById("js-error");

    const serverError =
        document.querySelector(".server-error");

    let error = "";

    // ================= VALIDATE =================

    if (!oldPass || !newPass || !confirmPass) {
        error = "Vui lòng nhập đầy đủ thông tin";
    } 
    else if (newPass.length < 6) {
        error = "Mật khẩu mới phải từ 6 ký tự trở lên";
    } 
    else if (newPass !== confirmPass) {
        error = "Mật khẩu xác nhận không khớp";
    }

    // ================= HIỂN THỊ LỖI =================

    if (error) {

        e.preventDefault();
        // Ẩn lỗi server cũ
        if (serverError) {
            serverError.style.display = "none";
        }
        // Hiện lỗi JS
        jsError.classList.remove("hidden");
        jsError.innerText = error;
    } else {
        jsError.classList.add("hidden");
    }

});

</script>