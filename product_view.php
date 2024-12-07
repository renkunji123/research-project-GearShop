<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ggshopdb"; // Đổi thành tên cơ sở dữ liệu của bạn

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy product_id từ URL
$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : 1;  // Mặc định product_id = 1 nếu không có trong URL

// Truy vấn dữ liệu sản phẩm từ cơ sở dữ liệu
$sql = "SELECT * FROM products WHERE product_id = $product_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Lấy thông tin sản phẩm
    $product = $result->fetch_assoc();
} else {
    echo "Product not found.";
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Product View</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- Header -->
    <header class="d-flex align-items-center justify-content-between py-3 px-4 border-bottom">
        <h1 class="h4">Gaming Gear Shop</h1>
    </header>

    <!-- Main Content -->
    <div class="container my-5">
        <h2 class="mb-4">Product View</h2>
        <div class="row align-items-center bg-light p-4 rounded shadow-sm mb-4">
            <!-- Product Image -->
            <div class="col-md-4 text-center">
                <img src="<?php echo $product['product_image']; ?>" alt="Product Image" class="img-fluid rounded">
            </div>

            <!-- Product Information -->
            <div class="col-md-8">
                <h3 class="fw-bold"><?php echo $product['product_name']; ?></h3>
                <p class="text-muted"><strong>ID sản phẩm:</strong> <?php echo $product['product_id']; ?></p>
                <p><strong>Mô tả:</strong> <?php echo $product['product_decription']; ?></p>
                <p class="text-muted"><strong>Danh mục:</strong> <?php echo $product['category_id']; ?></p>
                <p class="text-muted"><strong>Thương hiệu:</strong> <?php echo $product['brand_id']; ?></p>
                <p class="text-success fw-bold fs-4"><strong>Giá:</strong> <?php echo number_format($product['product_price'], 0, ',', '.'); ?> VND</p>
                <p class="text-muted"><strong>Tồn kho:</strong> <?php echo $product['stock_quantity']; ?> sản phẩm</p>

                <!-- Action Buttons -->
                <div class="mt-3">
                    <button class="btn btn-primary">Thêm vào giỏ hàng</button>
                </div>
            </div>
        </div>

        <!-- Total Section -->
        <div class="row mt-4">
            <div class="col-md-6">
                <a href="../research-project-GearShop/Frontend/home_page.html" class="btn btn-outline-primary">Tiếp tục mua sắm</a>
            </div>
            <div class="col-md-6 text-end">
                <h4 class="fw-bold">Tổng tiền: <?php echo number_format($product['product_price'], 0, ',', '.'); ?> VND</h4>
                <button class="btn btn-primary btn-lg mt-3">Thanh toán</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center text-lg-start bg-body-tertiary text-muted">
        <p>&copy; 2024 Gaming Gear Shop</p>
    </footer>
</body>

</html>
