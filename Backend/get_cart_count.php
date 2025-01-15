<?php
session_start();
include 'db.php'; // Đảm bảo kết nối DB

// Kiểm tra xem người dùng đã đăng nhập chưa
if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user']['id'];

    // Truy vấn số lượng sản phẩm trong giỏ hàng của người dùng
    $query = "SELECT SUM(cart_item_quantity) AS total_items FROM cart_item ci 
              JOIN carts c ON ci.cart_id = c.cart_id 
              WHERE c.user_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $totalItems = $data['total_items'] ? $data['total_items'] : 0;

    // Trả về số lượng sản phẩm trong giỏ hàng dưới dạng JSON
    echo json_encode(['totalItems' => $totalItems]);
} else {
    echo json_encode(['totalItems' => 0]); // Trả về 0 nếu chưa đăng nhập
}
?>
