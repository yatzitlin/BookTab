<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/BookTab');
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | BookTab' : 'BookTab - Nhà sách trực tuyến'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Be Vietnam Pro', sans-serif;
        }
    </style>
</head>
<body class="bg-white">
    <!-- HEADER -->
    <div id="preloader"><div class="loader"></div></div>
    <?php include __DIR__ . '/components/Header.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="max-w-6xl mx-auto px-4 lg:px-8 py-8 min-h-screen">
        <?php 
            if (isset($view_content) && file_exists($view_content)) {
                include $view_content;
            }
        ?>
    </main>

    <!-- FOOTER -->
    <?php include __DIR__ . '/components/Footer.php'; ?>
    <script>
        let index = 0;
        const slider = document.getElementById("slider");

        let autoSlide;

        function showSlide() {
            slider.style.transform = `translateX(-${index * 100}%)`;
        }

        // reset auto khi user click
        function resetAuto() {
            clearInterval(autoSlide);
            autoSlide = setInterval(nextSlide, 4000);
        }

        function nextSlide() {
            index = (index + 1) % slider.children.length;
            showSlide();
            resetAuto();
        }

        function prevSlide() {
            index = (index - 1 + slider.children.length) % slider.children.length;
            showSlide();
            resetAuto();
        }

        // start auto
        if (slider) {
            autoSlide = setInterval(nextSlide, 4000);
        }
    </script>
</body>
</html>
