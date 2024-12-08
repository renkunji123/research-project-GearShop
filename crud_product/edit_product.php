<?php
include '../db.php';  // Kết nối cơ sở dữ liệu

// Kiểm tra xem có truyền tham số 'id' không
if (isset($_GET['id'])) {
    // Lấy ID sản phẩm cần chỉnh sửa
    $product_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Truy vấn để lấy thông tin sản phẩm cần sửa
    $sql = "SELECT * FROM products WHERE product_id = '$product_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Lấy dữ liệu sản phẩm
        $row = $result->fetch_assoc();
    } else {
        echo "Sản phẩm không tồn tại.";
        exit;
    }
} else {
    echo "Không có sản phẩm nào để chỉnh sửa.";
    exit;
}

// Kiểm tra nếu form đã được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form và làm sạch
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $product_description = mysqli_real_escape_string($conn, $_POST['product_description']);
    $product_price = mysqli_real_escape_string($conn, $_POST['product_price']);
    $product_image = mysqli_real_escape_string($conn, $_POST['product_image']);
    $stock_quantity = mysqli_real_escape_string($conn, $_POST['stock_quantity']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $brand_id = mysqli_real_escape_string($conn, $_POST['brand_id']);

    // Truy vấn cập nhật thông tin sản phẩm
    $sql = "UPDATE products SET 
            product_name = '$product_name', 
            product_description = '$product_description', 
            product_price = '$product_price', 
            product_image = '$product_image', 
            stock_quantity = '$stock_quantity', 
            category_id = '$category_id', 
            brand_id = '$brand_id' 
            WHERE product_id = '$product_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Sản phẩm đã được cập nhật thành công.";
    } else {
        echo "Lỗi khi cập nhật sản phẩm: " . $conn->error;
    }
}

$conn->close();  // Đóng kết nối
?>

<!-- Form chỉnh sửa sản phẩm -->
<form method="POST" action="">
    <label for="product_name">Tên sản phẩm:</label>
    <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($row['product_name'], ENT_QUOTES, 'UTF-8'); ?>" required><br>

    <label for="product_description">Mô tả sản phẩm:</label>
    <textarea id="product_description" name="product_description" required><?php echo htmlspecialchars($row['product_description'], ENT_QUOTES, 'UTF-8'); ?></textarea><br>

    <label for="product_price">Giá sản phẩm:</label>
    <input type="number" id="product_price" name="product_price" value="<?php echo htmlspecialchars($row['product_price'], ENT_QUOTES, 'UTF-8'); ?>" required><br>

    <label for="product_image">Hình ảnh sản phẩm:</label>
    <input type="text" id="product_image" name="product_image" value="<?php echo htmlspecialchars($row['product_image'], ENT_QUOTES, 'UTF-8'); ?>" required><br>

    <label for="stock_quantity">Số lượng tồn kho:</label>
    <input type="number" id="stock_quantity" name="stock_quantity" value="<?php echo htmlspecialchars($row['stock_quantity'], ENT_QUOTES, 'UTF-8'); ?>" required><br>

    <label for="category_id">Danh mục sản phẩm:</label>
    <input type="number" id="category_id" name="category_id" value="<?php echo htmlspecialchars($row['category_id'], ENT_QUOTES, 'UTF-8'); ?>" required><br>

    <label for="brand_id">Thương hiệu sản phẩm:</label>
    <input type="number" id="brand_id" name="brand_id" value="<?php echo htmlspecialchars($row['brand_id'], ENT_QUOTES, 'UTF-8'); ?>" required><br>

    <button type="submit">Cập nhật sản phẩm</button>
</form>
