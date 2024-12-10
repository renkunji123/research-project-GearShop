<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ggshopdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

$category_id = intval($_POST['category_id']);

// Check if category is used in products
$check_sql = "SELECT COUNT(*) as count FROM products WHERE category_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("i", $category_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();
$check_row = $check_result->fetch_assoc();

if ($check_row['count'] > 0) {
    echo json_encode(["status" => "error", "message" => "Không thể xóa danh mục đã được sử dụng trong sản phẩm"]);
} else {
    $sql = "DELETE FROM categories WHERE category_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $category_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Danh mục đã được xóa"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Lỗi: " . $stmt->error]);
    }
}

$stmt->close();
$conn->close();
?>