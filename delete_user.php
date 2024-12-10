<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ggshopdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Kết nối thất bại: " . $conn->connect_error]));
}

$user_id = intval($_POST['user_id']);

// Ngăn admin xóa chính mình
if (isset($_SESSION['user']) && $_SESSION['user']['user_id'] == $user_id) {
    echo json_encode([
        "status" => "error", 
        "message" => "Không thể xóa tài khoản của chính bạn."
    ]);
    exit;
}

$sql = "DELETE FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success", 
        "message" => "Xóa người dùng thành công"
    ]);
} else {
    echo json_encode([
        "status" => "error", 
        "message" => "Có lỗi xảy ra: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>