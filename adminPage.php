<?php
session_start(); 
$isLoggedIn = isset($_SESSION['user']);
$userName = $isLoggedIn ? $_SESSION['user']['name'] : null;
$userRole = $isLoggedIn ? $_SESSION['user']['role'] : null;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ggshopdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn lấy danh sách sản phẩm
$sql = "SELECT p.product_id, p.product_name, p.product_description,  p.product_image, p.product_price, p.stock_quantity, c.category_name, b.brand_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.category_id 
        JOIN brands b ON p.brand_id = b.brand_id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>GGshop Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="path/to/jquery.min.js"></script>
    <style>
        .modal {
        display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.4); overflow: auto;
        }
        .modal-content {
        background-color: white; padding: 20px; border-radius: 5px; margin: 10% auto; width: 80%; max-width: 600px;
        }
        .close-btn {
        color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;
        }
        .close-btn:hover,
        .close-btn:focus {
        color: black; text-decoration: none; cursor: pointer;
        }
        label {
        font-weight: bold; display: block; margin-top: 10px;
        }
        input, textarea, select {
        width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ccc; border-radius: 4px;
        }
        button {
        background-color: #4CAF50; color: black; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;
        }
        button:hover {
        background-color: #45a049;
        }
    </style>
</head>
<body>
    <header class="d-flex align-items-center justify-content-between py-3 px-4 border-bottom">
        <!-- Danh sách liên kết điều hướng -->
        <ul class="nav me-auto">
            <li><a href="#" class="nav-link px-2 link-secondary">Home</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">Features</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">Pricing</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">FAQs</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">About</a></li>
        </ul>
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 col-lg-8 mb-3 mb-md-0">
                    <form class="d-flex" role="search">
                        <input type="search" class="form-control form-control-dark text-bg-light" 
                               placeholder="Search..." aria-label="Search">
                    </form>
                </div>
                <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-end gap-2">
                    <a href="/Frontend/cart.html" class="btn btn-info d-flex align-items-center">
                        <i class="bi bi-cart"></i> 
                        <span class="ms-2 d-none d-md-inline">Cart</span>
                    </a>
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" 
                                type="button" id="userDropdown" data-bs-toggle="dropdown" 
                                aria-expanded="false">
                                <i class="bi bi-person-circle " ></i>
                            <?= htmlspecialchars($_SESSION['user']['name']) ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="profile.php">
                            <i class="bi bi-person-fill"></i>
                             Profile
                            </a></li>
                            <?php if ($_SESSION['user']['role'] === 'ADMIN'): ?>
                                <li><a class="dropdown-item" href="admin_dashboard.php">
                                <i class="bi bi-shield-lock"></i> Admin
                                </a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">
                                <i class="bi bi-door-open"></i> 
                                Logout
                            </a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <button type="button" class="btn btn-outline-primary" 
                            onclick="window.location.href='login.php';">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                    </button>
                    <button type="button" class="btn btn-primary" 
                            onclick="window.location.href='register.php';">
                            <i class="bi bi-person-plus"></i> Register
                    </button>
                <?php endif; ?>
                </div>
            </div>
        </div> 
    </header>
    <div class="admin-page">
        <h1>Quản lý sản phẩm và người dùng</h1>
        <div class="tabs">
            <button class="tab-button active" onclick="showTab('products')">Sản phẩm</button>
            <button class="tab-button" onclick="showTab('users')">Người dùng</button>
        </div>
        <div class="tab-content" id="products" style="display: block;">
            <h2>Quản lý sản phẩm</h2>
            <button class="btn btn-primary add-btn" onclick="openModal('product')">➕ Thêm sản phẩm</button>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Mô tả</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Danh mục</th>
                            <th>Thương hiệu</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="product-list">
                        <?php
                        if ($result->num_rows > 0) {
                            // Hiển thị các sản phẩm
                            while($row = $result->fetch_assoc()) {
                                $product_image = !empty($row['product_image']) ? $row['product_image'] : 'path/to/default_image.jpg';
                                echo "<tr>
                                        <td>" . $row['product_id'] . "</td>
                                        <td><img src='" . $product_image . "' alt='Product Image' style='width: 100px; height: 100px; object-fit: cover;'></td>
                                        <td>" . $row['product_name'] . "</td>
                                        <td>" . $row['product_description'] . "</td>
                                        <td>" . $row['product_price'] . "</td>
                                        <td>" . $row['stock_quantity'] . "</td>
                                        <td>" . $row['category_name'] . "</td>
                                        <td>" . $row['brand_name'] . "</td>
                                        <td>
                                            <button class='btn btn-warning' onclick='openModal(\"product\", " . $row['product_id'] . ")'>Sửa</button>
                                            <button class='btn btn-danger' onclick='deleteProduct(" . $row['product_id'] . ")'>Xóa</button>
                                        </td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>Không có sản phẩm nào.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-content" id="users" style="display: none;">
                <h2>Quản lý người dùng</h2>
                <button class="btn btn-primary add-btn" onclick="openModal('user')">➕ Thêm người dùng</button>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên người dùng</th>
                            <th>Email</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="user-list">
                    </tbody>
            </table>
        </div>
    </div>
    <div class="modal" id="admin-modal" style="display: none;">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">×</span>
            <h3 id="modal-title">Thêm/Sửa Sản Phẩm</h3>
            <form id="admin-form" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="product_id" name="product_id">
                <label for="product_name">Tên Sản Phẩm:</label>
                <input type="text" id="product_name" name="product_name" required="">
                <label for="product_description">Mô Tả:</label>
                <textarea id="product_description" name="product_description" rows="4" required=""></textarea>
                <label for="product_price">Giá:</label>
                <input type="number" id="product_price" name="product_price" required="">
                <label for="stock_quantity">Số Lượng:</label>
                <input type="number" id="stock_quantity" name="stock_quantity" required="">
                <label for="category_id">Danh Mục:</label>
                <select id="category_id" name="category_id" required="">
                    <option value="">Chọn Danh Mục</option>
                </select>
                <label for="brand_id">Thương Hiệu:</label>
                <select id="brand_id" name="brand_id" required="">
                    <option value="">Chọn Thương Hiệu</option>
                </select>
                <label for="product_image">Ảnh Sản Phẩm:</label>
                <input type="file" id="product_image" name="product_image">
                <button type="submit" id="submit-button">Lưu</button>
            </form>
        </div>
    </div>
    <script>
        function showTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');
            document.querySelectorAll('.tab-button').forEach(button => button.classList.remove('active'));
            document.getElementById(tabId).style.display = 'block';
            document.querySelector(`.tab-button[onclick="showTab('${tabId}')"]`).classList.add('active');
        }
        function openModal(tyle, product_id = null) {
            const modal = document.getElementById('admin-modal');
            const form = document.getElementById('admin-form');
            const title = document.getElementById('modal-title');
            modal.style.display = 'block';
            if (tyle === 'product') {
                title.textContent = product_id ? 'Sửa Sản phẩm' : 'Thêm Sản phẩm';
                // Nếu có productId thì lấy thông tin sản phẩm
                if (product_id) {
                    // Gửi yêu cầu lấy thông tin sản phẩm
                    fetch('get_product.php?product_id=' + product_id)
                        .then(response => response.json())
                        .then(data => {
                            form.innerHTML = `
                                <input type="text" id="product_name" value="${data.product_name}" placeholder="Tên sản phẩm" class="form-control" required>
                                <input type="number" id="product_price" value="${data.product_price}" placeholder="Giá sản phẩm" class="form-control" required>
                                <input type="number" id="product_stock" value="${data.stock_quantity}" placeholder="Số lượng" class="form-control" required>
                                <input type="text" id="product_category" value="${data.category_name}" placeholder="Danh mục" class="form-control" required>
                                <input type="text" id="product_brand" value="${data.brand_name}" placeholder="Thương hiệu" class="form-control" required>
                                <input type="hidden" id="product_id" value="${data.product_id}">
                                <button type="submit" class="btn">Lưu</button>
                            `;
                        });
                } else {
                    form.innerHTML = `
                        <input type="text" id="product_name" placeholder="Tên sản phẩm" class="form-control" required>
                        <input type="number" id="product_price" placeholder="Giá sản phẩm" class="form-control" required>
                        <input type="number" id="product_stock" placeholder="Số lượng" class="form-control" required>
                        <input type="text" id="product_category" placeholder="Danh mục" class="form-control" required>
                        <input type="text" id="product_brand" placeholder="Thương hiệu" class="form-control" required>
                        <button type="submit" class="btn">Lưu</button>
                    `;
                } 
                form.onsubmit = function (e) {
                    e.preventDefault();
                    const productName = document.getElementById('product_name').value;
                    const productPrice = document.getElementById('product_price').value;
                    const productStock = document.getElementById('product_stock').value;
                    const productCategory = document.getElementById('category_id').value;
                    const productBrand = document.getElementById('brand_id').value;
                    const productId = document.getElementById('product_id') ? document.getElementById('product_id').value : null;
                    const formData = new FormData();
                    formData.append('name', productName);
                    formData.append('price', productPrice);
                    formData.append('stock', productStock);
                    formData.append('category', productCategory);
                    formData.append('brand', productBrand);
                    if (productId) formData.append('product_id', productId);
                    fetch('save_product.php', {
                        method: 'POST',
                        body: formData
                    }).then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        closeModal();
                        location.reload(); // Tải lại trang để cập nhật danh sách sản phẩm
                    });
                };
            } else {
                title.textContent = 'Thêm Người Dùng'; // Nếu bạn muốn cho chức năng thêm người dùng
                form.innerHTML = `
                    <input type="text" id="user_name" placeholder="Tên người dùng" class="form-control" required>
                    <input type="email" id="user_email" placeholder="Email" class="form-control" required>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                `;
            }
            
        }
        function closeModal() {
            const modal = document.getElementById('admin-modal');
            modal.style.display = 'none';
        }
        function deleteProduct(productId) {
        if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
            fetch('delete_product.php?id=' + productId)
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    location.reload();
                });
        }
    }
    </script>
    <div class="footer">
        <footer class="text-center text-lg-start bg-body-tertiary text-muted">
            <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
                <div class="me-5 d-none d-lg-block">
                    <span>Get connected with us on social networks:</span>
                </div>
                <div>
                    <a href="" class="me-4 text-reset"><i class="fab fa-facebook-f"></i></a>
                    <a href="" class="me-4 text-reset"><i class="fab fa-twitter"></i></a>
                    <a href="" class="me-4 text-reset"><i class="fab fa-google"></i></a>
                    <a href="" class="me-4 text-reset"><i class="fab fa-instagram"></i></a>
                    <a href="" class="me-4 text-reset"><i class="fab fa-linkedin"></i></a>
                    <a href="" class="me-4 text-reset"><i class="fab fa-github"></i></a>
                </div>
            </section>
            <section class="">
                <div class="container text-center text-md-start mt-5">
                    <div class="row mt-3">
                        <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                            <h6 class="text-uppercase fw-bold mb-4">
                                <i class="fas fa-gem me-3"></i>Company name
                            </h6>
                            <p>Here you can use rows and columns to organize your footer content. Lorem ipsum
                                dolor sit amet, consectetur adipisicing elit.</p>
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                            <h6 class="text-uppercase fw-bold mb-4">Products</h6>
                            <p><a href="#!" class="text-reset">Angular</a></p>
                            <p><a href="#!" class="text-reset">React</a></p>
                            <p><a href="#!" class="text-reset">Vue</a></p>
                            <p><a href="#!" class="text-reset">Laravel</a></p>
                        </div>
                        <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                            <h6 class="text-uppercase fw-bold mb-4">Useful links</h6>
                            <p><a href="#!" class="text-reset">Pricing</a></p>
                            <p><a href="#!" class="text-reset">Settings</a></p>
                            <p><a href="#!" class="text-reset">Orders</a></p>
                            <p><a href="#!" class="text-reset">Help</a></p>
                        </div>
                        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                            <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
                            <p><i class="fas fa-home me-3"></i> New York, NY 10012, US</p>
                            <p><i class="fas fa-envelope me-3"></i>info@example.com</p>
                            <p><i class="fas fa-phone me-3"></i> + 01 234 567 88</p>
                            <p><i class="fas fa-print me-3"></i> + 01 234 567 89</p>
                        </div>
                    </div>
                </div>
            </section>
</body>
</html>