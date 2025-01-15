<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ggshopdb";

// Tạo kết nối
$connection = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($connection->connect_error) {
    die("Kết nối thất bại: " . $connection->connect_error);
}

function getProductsByCategory($categoryId, $connection)
{
    $stmt = $connection->prepare("SELECT * FROM products WHERE category_id = ?");
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    $stmt->close();
    return $products;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>GGShop Trang Chủ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script>
        function showCategory(categoryId) {
            // Ẩn tất cả các div sản phẩm
            const productDivs = document.querySelectorAll('.products-category');
            productDivs.forEach(div => div.style.display = 'none');

            // Hiển thị div của danh mục được chọn
            const selectedDiv = document.getElementById(`category-${categoryId}`);
            if (selectedDiv) {
                selectedDiv.style.display = 'block';
                selectedDiv.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }
    </script>
</head>

<body>
    <header class="d-flex align-items-center justify-content-between py-3 px-4 border-bottom">
        <!-- Logo
        <a href="/" class="d-flex align-items-center text-dark text-decoration-none me-4">
            <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                <use xlink:href="#bootstrap"></use>
            </svg>
        </a> -->
        <!-- Danh sách liên kết điều hướng -->
        <ul class="nav me-auto">
            <li><a href="homepage.php" class="nav-link px-2 link-secondary">Trang Chủ</a></li>
            <!-- <li><a href="#" class="nav-link px-2 link-dark">Nổi Bật</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">Thanh Toán</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">FAQs</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">Thông Tin</a></li> -->
        </ul>
        <!-- Khu vực tìm kiếm và các nút -->
        <div class="container-fluid">
            <div class="row align-items-center">
                <!-- Ô tìm kiếm -->
                <div class="col-12 col-md-6 col-lg-8 mb-3 mb-md-0">
                    <form class="d-flex" role="search">
                        <input type="search" class="form-control form-control-dark text-bg-light"
                            placeholder="Search..." aria-label="Search">
                    </form>
                </div>

                <!-- Các nút hành động -->
                <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-end gap-2">
                    <!-- Nút Cart -->
                    <a href="cart.php" class="btn btn-info d-flex align-items-center position-relative">
                        <i class="bi bi-cart"></i>

                        <!-- Hiển thị số lượng sản phẩm trong giỏ hàng -->
                        <span id="cart-count"
                            class="badge bg-danger position-absolute top-0 start-100 translate-middle badge rounded-pill">
                            0 <!-- Đây là giá trị mặc định -->
                        </span>
                    </a>
                    <!-- Kiểm tra trạng thái đăng nhập -->
                    <?php if (isset($_SESSION['user'])): ?>
                        <!-- Nếu người dùng đã đăng nhập -->
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="userDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle "></i>
                                <?= htmlspecialchars($_SESSION['user']['name']) ?>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="profile/profile.php">
                                        <i class="bi bi-person-fill"></i>
                                        Hồ sơ
                                    </a></li>
                                <?php if ($_SESSION['user']['role'] === 'ADMIN'): ?>
                                    <li><a class="dropdown-item" href="adminPage.php">
                                            <i class="bi bi-shield-lock"></i> Quản Trị Viên
                                        </a></li>
                                <?php endif; ?>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="logout.php">
                                        <i class="bi bi-door-open"></i>
                                        Đăng Xuất
                                    </a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <!-- Nếu người dùng chưa đăng nhập -->
                        <button type="button" class="btn btn-outline-primary" onclick="window.location.href='login.php';">
                            <i class="bi bi-box-arrow-in-right"></i> Đăng Nhập
                        </button>
                        <button type="button" class="btn btn-primary" onclick="window.location.href='register.php';">
                            <i class="bi bi-person-plus"></i> Đăng Ký
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-3">
                <nav class="vertical-menu p-3" aria-label="Main navigation">
                    <ul class="list-unstyled mb-0">
                        <li class="menu-item mb-3">
                            <div class="d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                                data-bs-target="#gamingGear">
                                <span>Các Sản Phẩm Gaming</span>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                            <ul class="collapse list-unstyled ps-3 mt-2" id="gamingGear">
                                <li><a href="#" class="text-decoration-none" onclick="showCategory(1)">Chuột</a></li>
                                <li><a href="#" class="text-decoration-none" onclick="showCategory(2)">Bàn Phím</a></li>
                                <li><a href="#" class="text-decoration-none" onclick="showCategory(3)">Tai Nghe</a></li>
                                <li><a href="#" class="text-decoration-none" onclick="showCategory(4)">Màn Hình</a></li>
                                <li><a href="#" class="text-decoration-none" onclick="showCategory(5)">Bàn</a></li>
                                <li><a href="#" class="text-decoration-none" onclick="showCategory(6)">Ghế</a></li>
                            </ul>
                        </li>
                    </ul>

                </nav>
            </div>
            <div class="col-md-9">
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner rounded-3">
                        <div class="carousel-item active">
                            <img src="https://images.unsplash.com/photo-1587202372634-32705e3bf49c"
                                class="d-block w-100" alt="Gaming Setup">
                        </div>
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1593640495253-23196b27a87f"
                                class="d-block w-100" alt="Gaming Keyboard">
                        </div>
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1625842268584-8f3296236761"
                                class="d-block w-100" alt="Gaming Mouse">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Trước</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Tiếp</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php
    // Tạo các thẻ div chứa sản phẩm theo từng danh mục
    $categories = [
        1 => 'Mouse',
        2 => 'Keyboard',
        3 => 'Headphone',
        4 => 'Monitor',
        5 => 'Table',
        6 => 'Chair',
    ];

    foreach ($categories as $categoryId => $categoryName) {
        $products = getProductsByCategory($categoryId, $connection);
        echo "<div class='products-category' id='category-$categoryId' style='display: none;'>";
        echo "<h2>$categoryName</h2>";
        echo "<div class='row'>";
        foreach ($products as $product) {
            echo "<div class='col-md-4 mb-4'>";
            echo "<div class='card'>";
            echo "<img src='{$product['product_image']}' class='card-img-top' alt='{$product['product_name']}'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>{$product['product_name']}</h5>";
            echo "<p class='card-text'>{$product['product_description']}</p>";
            echo "<p class='card-text'><strong>Giá: </strong>{$product['product_price']} VNĐ</p>";
            echo "<p class='card-text'><strong>Số lượng: </strong>{$product['stock_quantity']}</p>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
        echo "</div>";
    }
    ?>
        
</body>

</html>