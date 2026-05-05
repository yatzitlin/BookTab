<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/MobileS');
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
    <?php include dirname(__FILE__) . '/components/Header.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="max-w-6xl mx-auto px-4 lg:px-8 py-8 min-h-screen">
        <?php 
            if (isset($view_content) && file_exists($view_content)) {
                include $view_content;
            }
        ?>
    </main>

    <!-- FOOTER -->
    <?php include dirname(__FILE__) . '/components/Footer.php'; ?>
</body>
</html>
