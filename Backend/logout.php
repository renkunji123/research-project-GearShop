<?php
// Bắt đầu session
session_start();

// Xóa tất cả thông tin trong session
session_unset();
session_destroy();

// Chuyển hướng về trang chủ hoặc trang login
header("Location: ../Frontend/homepage.php");
exit();
?>
