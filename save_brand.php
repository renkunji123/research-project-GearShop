<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ggshopdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

$brand_id = isset($_POST['brand_id']) ? intval($_POST['brand_id']) : null;
$brand_name = $_POST['brand_name'];
$brand_description = $_POST['brand_description'];

if ($brand_id) {
    // Update existing brand
    $sql = "UPDATE brands SET brand_name = ?, brand_description = ? WHERE brand_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $brand_name, $brand_description, $brand_id);
} else {
    // Insert new brand
    $sql = "INSERT INTO brands (brand_name, brand_description) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $brand_name, $brand_description);
}

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => $brand_id ? "Thương hiệu đã được cập nhật" : "Thương hiệu đã được thêm mới"]);
} else {
    echo json_encode(["status" => "error", "message" => "Lỗi: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>