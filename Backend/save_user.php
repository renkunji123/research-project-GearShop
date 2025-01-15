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

// Lấy dữ liệu từ form
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
$user_fullname = $_POST['user_fullname'];
$email = $_POST['email'];
$phone_number = $_POST['phone_number'] ?? null;
$user_address = $_POST['user_address'] ?? null;
$user_gender = $_POST['user_gender'] ?? null;
$role = $_POST['role'] ?? 'CUSTOMER';

// Kiểm tra email đã tồn tại chưa
$check_email_sql = $user_id 
    ? "SELECT * FROM users WHERE email = ? AND user_id != ?" 
    : "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($check_email_sql);
$user_id 
    ? $stmt->bind_param("si", $email, $user_id)
    : $stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode([
        "status" => "error", 
        "message" => "Email đã tồn tại trong hệ thống."
    ]);
    exit;
}

// Nếu là thêm mới
if (!$user_id) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (user_fullname, email, phone_number, user_address, user_gender, password, role) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $user_fullname, $email, $phone_number, $user_address, $user_gender, $password, $role);
} 
// Nếu là sửa
else {
    $sql = "UPDATE users SET user_fullname=?, email=?, phone_number=?, user_address=?, user_gender=?, role=? 
            WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $user_fullname, $email, $phone_number, $user_address, $user_gender, $role, $user_id);
}

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success", 
        "message" => $user_id ? "Cập nhật người dùng thành công" : "Thêm người dùng thành công"
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