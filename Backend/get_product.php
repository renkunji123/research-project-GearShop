<?php
// Kết nối với cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ggshopdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy product_id từ URL
$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : 0;

if ($product_id > 0) {
    // Truy vấn lấy thông tin sản phẩm
    $sql = "SELECT p.product_id, p.product_name, p.product_description, p.product_price, p.stock_quantity, p.category_id, p.brand_id, p.product_image, c.category_name, b.brand_name 
            FROM products p 
            JOIN categories c ON p.category_id = c.category_id 
            JOIN brands b ON p.brand_id = b.brand_id 
            WHERE p.product_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        echo json_encode($product);
    } else {
        echo json_encode(['error' => 'Không tìm thấy sản phẩm.']);
    }
} else {
    echo json_encode(['error' => 'ID sản phẩm không hợp lệ.']);
}

$conn->close();
?>
