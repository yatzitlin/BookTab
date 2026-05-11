<div class="max-w-md mx-auto my-12">
    <div class="bg-white rounded-lg shadow-lg p-8 border border-gray-200">
        <h1 class="text-3xl font-bold text-gray-900 mb-2 text-center">Đăng nhập</h1>
        <p class="text-center text-gray-600 mb-8">Chào mừng quay trở lại BookTab</p>

        <!-- Success Message (từ registered) -->
        <?php if (!empty($_GET['registered'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                <p>✓ Đăng ký thành công! Vui lòng đăng nhập với tài khoản của bạn.</p>
            </div>
        <?php endif; ?>

            <!-- Unauthorized Access Message -->
            <?php if (!empty($_GET['error']) && $_GET['error'] === 'unauthorized'): ?>
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg mb-6">
                    <p>Bạn không có quyền truy cập trang Admin. Vui lòng đăng nhập bằng tài khoản Admin.</p>
                </div>
            <?php endif; ?>

            <?php if (!empty($_GET['error']) && $_GET['error'] === 'qna_login_required'): ?>
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg mb-6">
                    <p>Bạn cần đăng nhập để đặt câu hỏi.</p>
                </div>
            <?php endif; ?>

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

        <!-- Login Form -->
        <form action="<?php echo BASE_URL; ?>/public/index.php?action=login" method="POST" class="space-y-4">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

            <!-- Username Field -->
            <div>
                <label for="username" class="block text-sm font-semibold text-gray-900 mb-2">Tên đăng nhập</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 focus:outline-none transition"
                    placeholder="Nhập tên đăng nhập"
                    value="<?php echo htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                >
            </div>

            <!-- Password Field -->
            <div>
                <label for="mat_khau" class="block text-sm font-semibold text-gray-900 mb-2">Mật khẩu</label>
                <input 
                    type="password" 
                    id="mat_khau" 
                    name="mat_khau" 
                    required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 focus:outline-none transition"
                    placeholder="Nhập mật khẩu"
                >
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    <span class="text-sm text-gray-600">Ghi nhớ tôi</span>
                </label>
                <a href="#" class="text-sm text-red-500 hover:text-red-600 transition">
                    Quên mật khẩu?
                </a>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit"
                class="w-full bg-red-500 text-white py-3 rounded-full font-bold text-lg hover:bg-red-600 transition-all transform hover:-translate-y-1 shadow-lg hover:shadow-xl mt-6"
            >
                Đăng nhập
            </button>
        </form>

        <!-- Divider -->
        <div class="flex items-center my-6">
            <div class="flex-1 border-t border-gray-300"></div>
        </div>

        <!-- Don't have account -->
        <p class="text-center text-gray-600 mt-6">
            Chưa có tài khoản? 
            <a href="<?php echo BASE_URL; ?>/public/index.php?action=register" class="text-red-500 font-bold hover:text-red-600 transition">
                Đăng ký ngay
            </a>
        </p>

        <!-- Back to Home -->
        <p class="text-center text-gray-600 mt-4">
            <a href="<?php echo BASE_URL; ?>/public/index.php?page=home" class="text-gray-600 hover:text-gray-900 transition">
                Quay lại trang chủ
            </a>
        </p>
    </div>
</div>

<style>
    /* Password eye toggle */
    #password {
        padding-right: 40px;
    }
</style>

<script>
    // Toggle password visibility
    const passwordInput = document.getElementById('password');
    const toggleBtn = document.createElement('button');
    toggleBtn.type = 'button';
    toggleBtn.innerHTML = '<i class="fas fa-eye-slash"></i>';
    toggleBtn.style.cssText = `
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #999;
    `;

    passwordInput.parentElement.style.position = 'relative';
    passwordInput.parentElement.appendChild(toggleBtn);

    toggleBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
        } else {
            passwordInput.type = 'password';
            toggleBtn.innerHTML = '<i class="fas fa-eye-slash"></i>';
        }
    });
</script>
