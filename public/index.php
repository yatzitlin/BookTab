<?php
ob_start(); 
?>
<h1 class="text-center">ĐÉO BIẾT VIẾT GÌ</h1>
<?php
$contentView = ob_get_clean();

// GỌI TEMPLATE RA ĐỂ HIỂN THỊ
require_once '../app/views/template.php';
?>