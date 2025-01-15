<?php
// Kết nối đến database
$conn = new mysqli('localhost', 'root', '', 'ggshopdb');
// $conn = new mysqli('127.0.0.1', 'usera', 'passa', 'ggshopdb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy dữ liệu
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
echo json_encode($data);
?>
