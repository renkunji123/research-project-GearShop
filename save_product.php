<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ggshopdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;$product_name = $_POST['product_name'];
$product_name = $_POST['product_name'];
$product_image = $_POST['product_image']; // Lấy URL của sản phẩm
$product_description = $_POST['product_description'];
$product_price = $_POST['product_price'];
$product_stock = $_POST['product_stock'];
$category_id = $_POST['category_id'];  // Lấy category_id từ form
$brand_id = $_POST['brand_id'];  // Lấy brand_id từ form

// Kiểm tra xem category_id có hợp lệ không
$category_check_query = "SELECT * FROM categories WHERE category_id = ?";
$stmt = $conn->prepare($category_check_query);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo json_encode(["message" => "Category ID không hợp lệ.". $category_id]);
    exit;
}

// Kiểm tra xem brand_id có hợp lệ không
$brand_check_query = "SELECT * FROM brands WHERE brand_id = ?";
$stmt = $conn->prepare($brand_check_query);
$stmt->bind_param("i", $brand_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo json_encode(["message" => "Brand ID không hợp lệ."]);
    exit;
}

if ($product_id) {
    // Cập nhật thông tin sản phẩm
    $update_query = "UPDATE products SET product_name = ?, product_description = ?, product_image = ?, product_price = ?, stock_quantity = ?, category_id = ?, brand_id = ? WHERE product_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssiiis", $product_name, $product_description, $product_image, $product_price, $product_stock, $category_id, $brand_id, $product_id);

    // Kiểm tra xem câu lệnh SQL có thực thi thành công không
    if ($stmt->execute()) {
        echo json_encode(["message" => "Sản phẩm đã được cập nhật."]);
    } else {
        echo json_encode(["message" => "Đã xảy ra lỗi khi cập nhật sản phẩm."]);
    }
} else {
    // Thêm sản phẩm mới
    $insert_query = "INSERT INTO products (product_name, product_description, product_image, product_price, stock_quantity, category_id, brand_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ssssiii", $product_name, $product_description, $product_image, $product_price, $product_stock, $category_id, $brand_id);

    // Kiểm tra xem câu lệnh SQL có thực thi thành công không
    if ($stmt->execute()) {
        echo json_encode(["message" => "Sản phẩm mới đã được thêm."]);
    } else {
        echo json_encode(["message" => "Đã xảy ra lỗi khi thêm sản phẩm."]);
    }
}
?>