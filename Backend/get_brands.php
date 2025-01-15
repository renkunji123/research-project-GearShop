<?php
// Thông tin kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$database = "ggshopdb";

// Kết nối cơ sở dữ liệu
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn danh sách các danh mục
$sql = "SELECT brand_id, brand_name FROM brands";
$result = $conn->query($sql);

$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Trả về dữ liệu dạng JSON
header('Content-Type: application/json');
echo json_encode($categories);

$conn->close();
?>