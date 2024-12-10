<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ggshopdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy dữ liệu từ fetch
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['product_id'], $data['quantity'], $data['price'])) {
    $userId = $_SESSION['user_id']; // Lấy ID người dùng từ session
    $productId = $data['product_id'];
    $quantity = $data['quantity'];
    $unitPrice = $data['price'];

    try {
        // Kiểm tra giỏ hàng của người dùng
        $stmt = $conn->prepare("SELECT cart_id FROM carts WHERE user_id = ?");
        $stmt->execute([$userId]);
        $cart = $stmt->fetch();

        if (!$cart) {
            // Nếu chưa có giỏ hàng, tạo mới
            $stmt = $conn->prepare("INSERT INTO carts (user_id, sub_total) VALUES (?, 0)");
            $stmt->execute([$userId]);
            $cartId = $conn->lastInsertId();
        } else {
            $cartId = $cart['cart_id'];
        }

        // Kiểm tra sản phẩm trong giỏ hàng
        $stmt = $conn->prepare("SELECT cart_item_id, cart_item_quantity FROM cart_item WHERE cart_id = ? AND product_id = ?");
        $stmt->execute([$cartId, $productId]);
        $item = $stmt->fetch();

        if ($item) {
            // Nếu đã có sản phẩm, cập nhật số lượng và tổng tiền
            $newQuantity = $item['cart_item_quantity'] + $quantity;
            $stmt = $conn->prepare("UPDATE cart_item SET cart_item_quantity = ?, sub_total_amount = ? WHERE cart_item_id = ?");
            $stmt->execute([$newQuantity, $newQuantity * $unitPrice, $item['cart_item_id']]);
        } else {
            // Nếu chưa có, thêm sản phẩm mới
            $stmt = $conn->prepare("INSERT INTO cart_item (cart_id, product_id, cart_item_quantity, unit_price, sub_total_amount) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$cartId, $productId, $quantity, $unitPrice, $quantity * $unitPrice]);
        }

        // Cập nhật tổng tiền giỏ hàng
        $stmt = $conn->prepare("UPDATE carts SET sub_total = (SELECT SUM(sub_total_amount) FROM cart_item WHERE cart_id = ?) WHERE cart_id = ?");
        $stmt->execute([$cartId, $cartId]);

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>
