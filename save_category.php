<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ggshopdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

$category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : null;
$category_name = $_POST['category_name'];
$category_description = $_POST['category_description'];

if ($category_id) {
    // Update existing category
    $sql = "UPDATE categories SET category_name = ?, category_description = ? WHERE category_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $category_name, $category_description, $category_id);
} else {
    // Insert new category
    $sql = "INSERT INTO categories (category_name, category_description) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $category_name, $category_description);
}

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => $category_id ? "Danh mục đã được cập nhật" : "Danh mục đã được thêm mới"]);
} else {
    echo json_encode(["status" => "error", "message" => "Lỗi: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>