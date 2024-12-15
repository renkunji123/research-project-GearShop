<?php
session_start();

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ggshopdb');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra user_id trong session
if (isset($_SESSION['user']['id'])) {
    $user_id = $_SESSION['user']['id'];

    // Lấy sản phẩm từ giỏ hàng
    $sql = "SELECT
    c.cart_id, 
    ci.product_id, 
    ci.cart_item_quantity, 
    p.product_name, 
    p.product_price, 
    p.product_image
FROM 
    cart_item ci
JOIN 
    carts c ON ci.cart_id = c.cart_id
JOIN 
    products p ON ci.product_id = p.product_id
WHERE 
    c.user_id = ?
";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $cart_items = [];
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
    }

    echo json_encode($cart_items);
} else {
    echo json_encode(['error' => 'Người dùng chưa đăng nhập']);
}
