<?php
// Kết nối tới database
$servername = "localhost";
$username = "root";
$password = "";
$database = "ggshopdb";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy dữ liệu từ bảng brands
$sql = "SELECT * FROM brands";
$result = $conn->query($sql);

$brands = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $brands[] = $row;
    }
}

// Trả về dữ liệu dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($brands);

$conn->close();
?>
