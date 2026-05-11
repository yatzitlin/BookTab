<?php
if (!isset($_SESSION['success'])) $_SESSION['success'] = null;
if (!isset($_SESSION['error'])) $_SESSION['error'] = null;

$success = $_SESSION['success'];
$error = $_SESSION['error'];

// clear flash message sau khi lấy
unset($_SESSION['success'], $_SESSION['error']);
?>

<div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-6">Đổi mật khẩu</h1>

    <!-- SUCCESS -->
    <?php if ($success): ?>
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <!-- ERROR -->
    <?php if ($error): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- FORM -->
    <form method="POST" action="index.php?page=change-password" id="changePassForm" class="space-y-4">

        <div>
            <label class="text-sm">Mật khẩu hiện tại</label>
            <input type="password" name="old_password"
                   class="w-full border p-3 rounded mt-1">
        </div>

        <div>
            <label class="text-sm">Mật khẩu mới</label>
            <input type="password" name="new_password"
                   class="w-full border p-3 rounded mt-1">
        </div>

        <div>
            <label class="text-sm">Xác nhận mật khẩu</label>
            <input type="password" name="confirm_password"
                   class="w-full border p-3 rounded mt-1">
        </div>

        <button class="w-full bg-red-500 text-white py-3 rounded font-semibold hover:bg-red-600">
            Đổi mật khẩu
        </button>

    </form>

    <a href="index.php?page=profile"
       class="block text-center mt-4 text-blue-500">
        ← Quay lại
    </a>
</div>

<!-- ================= CLIENT VALIDATION ================= -->
<script>
document.getElementById("changePassForm").addEventListener("submit", function(e) {

    const oldPass = document.querySelector("[name='old_password']").value.trim();
    const newPass = document.querySelector("[name='new_password']").value.trim();
    const confirmPass = document.querySelector("[name='confirm_password']").value.trim();

    let error = "";

    if (!oldPass || !newPass || !confirmPass) {
        error = "Vui lòng nhập đầy đủ thông tin";
    } 
    else if (newPass.length < 6) {
        error = "Mật khẩu mới phải từ 6 ký tự trở lên";
    } 
    else if (newPass !== confirmPass) {
        error = "Mật khẩu xác nhận không khớp";
    }

    if (error) {
        e.preventDefault();

        let box = document.getElementById("js-error");

        if (!box) {
            box = document.createElement("div");
            box.id = "js-error";
            box.className = "mb-4 p-3 bg-red-100 text-red-700 rounded";
            document.querySelector("form").prepend(box);
        }

        box.innerText = error;
    }
});
</script>