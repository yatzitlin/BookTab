<div class="max-w-md mx-auto my-12">
    <div class="bg-white rounded-lg shadow-lg p-8 border border-gray-200">
        <h1 class="text-3xl font-bold text-gray-900 mb-2 text-center">Đăng ký tài khoản</h1>
        <p class="text-center text-gray-600 mb-8">Tạo tài khoản BookTab mới</p>

        <!-- Error Messages -->
        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <?php 
                foreach ($errors as $error): 
                ?>
                    <p class="mb-2">• <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Register Form -->
        <form action="<?php echo BASE_URL; ?>/public/index.php?action=register" method="POST" class="space-y-4">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-semibold text-gray-900 mb-2">Tên đăng nhập</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    required
                    minlength="3"
                    maxlength="50"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 focus:outline-none transition"
                    placeholder="Tối thiểu 3 ký tự"
                    value="<?php echo htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                >
                <p class="text-sm text-gray-500 mt-1">- Tên đăng nhập phải từ 3-50 ký tự, không có khoảng trắng</p>
            </div>

            <!-- Họ và Tên Đệm -->
            <div>
                <label for="ho_va_ten_dem" class="block text-sm font-semibold text-gray-900 mb-2">Họ và tên đệm</label>
                <input 
                    type="text" 
                    id="ho_va_ten_dem" 
                    name="ho_va_ten_dem" 
                    required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 focus:outline-none transition"
                    placeholder="Ví dụ: Nguyễn Văn"
                    value="<?php echo htmlspecialchars($_POST['ho_va_ten_dem'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                >
            </div>

            <!-- Tên -->
            <div>
                <label for="ten" class="block text-sm font-semibold text-gray-900 mb-2">Tên</label>
                <input 
                    type="text" 
                    id="ten" 
                    name="ten" 
                    required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 focus:outline-none transition"
                    placeholder="Ví dụ: Hiệp"
                    value="<?php echo htmlspecialchars($_POST['ten'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                >
            </div>
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 focus:outline-none transition"
                    placeholder="name@example.com"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                >
            </div>

            <!-- Phone -->
            <div>
                <label for="so_dien_thoai" class="block text-sm font-semibold text-gray-900 mb-2">Số điện thoại (không bắt buộc)</label>
                <input 
                    type="tel" 
                    id="so_dien_thoai" 
                    name="so_dien_thoai"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 focus:outline-none transition"
                    placeholder="Nhập số điện thoại"
                    value="<?php echo htmlspecialchars($_POST['so_dien_thoai'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                >
            </div>

            <!-- Password -->
            <div>
                <label for="mat_khau" class="block text-sm font-semibold text-gray-900 mb-2">Mật khẩu</label>
                <input 
                    type="password" 
                    id="mat_khau" 
                    name="mat_khau" 
                    required
                    minlength="6"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 focus:outline-none transition"
                    placeholder="Ít nhất 6 ký tự"
                >
                <p class="text-sm text-gray-500 mt-1">- Lưu ý: Sử dụng mật khẩu mạnh: kết hợp chữ cái, số, và ký tự đặc biệt</p>
            </div>

            <!-- Xác nhận Password -->
            <div>
                <label for="mat_khau_confirm" class="block text-sm font-semibold text-gray-900 mb-2">Xác nhận mật khẩu</label>
                <input 
                    type="password" 
                    id="mat_khau_confirm" 
                    name="mat_khau_confirm" 
                    required
                    minlength="6"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 focus:outline-none transition"
                    placeholder="Nhập lại mật khẩu"
                >
            </div>

            <!-- Submit -->
            <button 
                type="submit"
                class="w-full bg-red-500 text-white py-3 rounded-full font-bold text-lg hover:bg-red-600 transition-all transform hover:-translate-y-1 shadow-lg hover:shadow-xl mt-6"
            >
                Đăng ký
            </button>
        </form>

        <!-- Đã có tài khoản rồi -> chuyển qua login -->
        <p class="text-center text-gray-600 mt-6">
            Đã có tài khoản? 
            <a href="<?php echo BASE_URL; ?>/public/index.php?action=login" class="text-red-500 font-bold hover:text-red-600 transition">
                Đăng nhập ngay
            </a>
        </p>

        <!-- Quay lại trang chủ -->
        <p class="text-center text-gray-600 mt-4">
            <a href="<?php echo BASE_URL; ?>/public/index.php?page=home" class="text-gray-600 hover:text-gray-900 transition">
                Quay lại trang chủ
            </a>
        </p>
    </div>
</div>

<script>
    // Client-side validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const password = document.getElementById('mat_khau').value;
        const passwordConfirm = document.getElementById('mat_khau_confirm').value;

        if (password !== passwordConfirm) {
            e.preventDefault();
            alert('Mật khẩu và xác nhận mật khẩu không khớp!');
        }
    });
</script>
