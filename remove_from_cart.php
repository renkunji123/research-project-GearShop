<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ggshopdb";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['product_id']) && isset($_POST['cart_id'])) {
    $product_id = $_POST['product_id'];
    $cart_id = $_POST['cart_id'];

    // Xóa sản phẩm khỏi giỏ hàng
    $sql = "DELETE FROM cart_item WHERE product_id = ? AND cart_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $product_id, $cart_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Sản phẩm đã được xóa khỏi giỏ hàng']);
    } 
}
?>
