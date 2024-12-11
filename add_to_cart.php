<?php
session_start();
include 'db.php'; // Đảm bảo đã include kết nối DB

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user'])) {
    echo "Bạn cần đăng nhập trước khi thêm sản phẩm vào giỏ hàng!";
    exit;
}

$userId = $_SESSION['user']['id']; // ID người dùng từ session
$productId = $_POST['product_id']; // ID sản phẩm gửi qua từ form
$quantity = $_POST['quantity']; // Số lượng sản phẩm
$unitPrice = $_POST['unit_price']; // Giá sản phẩm gửi qua từ form

// Kiểm tra sản phẩm có tồn tại trong bảng products không
$checkProductQuery = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($checkProductQuery);
$stmt->bind_param("i", $productId);
$stmt->execute();
$productResult = $stmt->get_result();

if ($productResult->num_rows === 0) {
    echo "Sản phẩm không tồn tại!";
    exit;
}

// Kiểm tra xem người dùng đã có giỏ hàng chưa
$checkCartQuery = "SELECT * FROM carts WHERE user_id = ?";
$stmt = $conn->prepare($checkCartQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Nếu giỏ hàng đã tồn tại, lấy cart_id
    $cart = $result->fetch_assoc();
    $cartId = $cart['cart_id'];
} else {
    // Nếu giỏ hàng chưa có, tạo mới giỏ hàng
    $createCartQuery = "INSERT INTO carts (user_id, sub_total) VALUES (?, 0)";
    $stmt = $conn->prepare($createCartQuery);
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    // Lấy cart_id mới tạo
    $cartId = $stmt->insert_id;
}

// Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
$checkItemQuery = "SELECT * FROM cart_item WHERE cart_id = ? AND product_id = ?";
$stmt = $conn->prepare($checkItemQuery);
$stmt->bind_param("ii", $cartId, $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Nếu sản phẩm đã có trong giỏ hàng, cập nhật số lượng
    $cartItem = $result->fetch_assoc();
    $newQuantity = $cartItem['cart_item_quantity'] + $quantity;
    $newSubTotal = $newQuantity * $unitPrice;

    $updateItemQuery = "UPDATE cart_item SET cart_item_quantity = ?, sub_total_amount = ? WHERE cart_item_id = ?";
    $stmt = $conn->prepare($updateItemQuery);
    $stmt->bind_param("idi", $newQuantity, $newSubTotal, $cartItem['cart_item_id']);
    $stmt->execute();
} else {
    // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
    $quantity = (int) $_POST['quantity']; // Sử dụng ép kiểu (int) cho quantity
    $unitPrice = (float) $_POST['unit_price']; // Sử dụng ép kiểu (float) cho unit_price
    $subTotalAmount = $quantity * $unitPrice;
    $insertItemQuery = "INSERT INTO cart_item (cart_id, product_id, cart_item_quantity, unit_price, sub_total_amount) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertItemQuery);
    $stmt->bind_param("iiidd", $cartId, $productId, $quantity, $unitPrice, $subTotalAmount);
    $stmt->execute();
}

// Cập nhật lại tổng giá trị giỏ hàng
$updateCartQuery = "UPDATE carts SET sub_total = (SELECT SUM(sub_total_amount) FROM cart_item WHERE cart_id = ?) WHERE cart_id = ?";
$stmt = $conn->prepare($updateCartQuery);
$stmt->bind_param("ii", $cartId, $cartId);
$stmt->execute();

echo "Sản phẩm đã được thêm vào giỏ hàng!";

?>