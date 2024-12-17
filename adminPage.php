<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ggshopdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'ADMIN') {
    echo "Bạn không có quyền truy cập trang này.";
    exit();
}
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
    <title>GGshop Trang Chủ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            overflow: auto;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin: 10% auto;
            width: 80%;
            max-width: 600px;
        }

        .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-btn:hover,
        .close-btn:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #4CAF50;
            color: black;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
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
            <li><a href="homepage.php" class="nav-link px-2 link-secondary">Trang Chủ</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">Nổi Bật</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">Thanh Toán</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">FAQs</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">Thông Tin</a></li>
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
                    <?php if (isset($_SESSION['user'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="userDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle "></i>
                                <?= htmlspecialchars($_SESSION['user']['name']) ?>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="profile.php">
                                        <i class="bi bi-person-fill"></i>
                                        Trang Cá Nhân
                                    </a></li>
                                <?php if ($_SESSION['user']['role'] === 'ADMIN'): ?>
                                    <!-- <li><a class="dropdown-item" href="admin_dashboard.php" >
                                            <i class="bi bi-shield-lock"></i> Admin
                                        </a></li> -->
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
    <div class="admin-page">
        <h1>Quản lý sản phẩm và người dùng</h1>
        <div class="tabs">
            <button class="tab-button active" onclick="showTab('products')">Sản phẩm</button>
            <button class="tab-button" onclick="showTab('users')">Người dùng</button>
            <button class="tab-button" onclick="showTab('categories')"> Danh mục</button>
            <button class="tab-button" onclick="showTab('brands')"> Thương hiệu</button>
            <button class="tab-button" onclick="showTab('orders')"> Đơn hàng</button>
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
                        while ($row = $result->fetch_assoc()) {
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
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Giới tính</th>
                        <th>Vai trò</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody id="user-list">

                </tbody>
            </table>
        </div>
        <div class="tab-content" id="categories" style="display: none;">
            <h2>Quản lý Danh mục</h2>
            <button class="btn btn-primary add-btn" onclick="openModal('category')">➕ Thêm danh mục</button>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Mô tả</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody id="category-list">
                </tbody>
            </table>
        </div>
        <div class="tab-content" id="brands" style="display: none;">
            <h2>Quản lý Thương hiệu</h2>
            <button class="btn btn-primary add-btn" onclick="openModal('brand')">➕ Thêm thương hiệu</button>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên Thương hiệu</th>
                        <th>Mô tả</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody id="brand-list">

                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-content" id="orders" style="display: none;">
        <h2>Quản lý Đơn hàng</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Người dùng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái đơn hàng</th>
                    <th>Phương thức thanh toán</th>
                    <th>Ngày thanh toán</th>
                    <th>Trạng thái thanh toán</th>
                    <th>Địa chỉ giao hàng</th>
                    <th>Ngày giao hàng</th>
                    <th>Trạng thái giao hàng</th>

                </tr>
            </thead>
            <tbody id="order-list">
                <!-- Dữ liệu đơn hàng sẽ được thêm vào đây bởi JavaScript -->
            </tbody>
        </table>
    </div>
    <div class="modal" id="admin-modal" style="display: none;">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">×</span>
            <h3 id="modal-title">Thêm/Sửa Sản Phẩm</h3>
            <form id="admin-form" method="POST" enctype="multipart/form-data">
            </form>
        </div>
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
                                <label for="product_image">Hình Ảnh:</label>
                                <img src='${data.product_image}' alt='Product Image' style='display: flex; width: 400px; height: 400px; margin:auto; object-fit: cover;'>

                                <label for="product_image">Sửa URL hình ảnh:</label>
                                <input id="product_image" value="${data.product_image}" class="form-control" placeholder="Nhập link hình ảnh cần đổi">

                                <label for="product_name">Tên Sản Phẩm:</label>
                                <input id="product_name" value="${data.product_name}" placeholder="Tên sản phẩm" class="form-control" required>

                                <label for="product_description">Mô Tả:</label>
                                <textarea type="text" id="product_description" value="" placeholder="Mô tả" class="form-control" required>${data.product_description}</textarea>
                                
                                <label for="product_price">Giá:</label>
                                <input type="number" id="product_price" value="${data.product_price}" placeholder="Giá sản phẩm" class="form-control" required>
                                
                                <label for="stock_quantity">Số Lượng:</label>
                                <input type="number" id="product_stock" value="${data.stock_quantity}" placeholder="Số lượng" class="form-control" required>
                                
                                <label for="category_id">Danh Mục:</label>
                                <select id="category_id" class="form-control" required>
                                </select>
                                <label for="brand_id">Thương Hiệu:</label>
                                <select id="brand_id" class="form-control" required>
                                    <option value='${data.brand_id}'>${data.brand_name} </option>
                                </select>
                                <input type="hidden" id="product_id" value="${data.product_id}">
                                <button type="submit" class="btn" style="background-color: green; color: white;">Lưu</button>
                            `;
                            fetch('get_categories.php')
                                .then(response => response.json())
                                .then(categories => {
                                    const categorySelect = document.getElementById('category_id');
                                    categories.forEach(category => {
                                        if (categories.category_id != data.category_id) {
                                            categorySelect.innerHTML += `<option value="${category.category_id}">${category.category_name}</option>`;
                                        }
                                    });
                                });
                            fetch('get_brands.php')
                                .then(response => response.json())
                                .then(brands => {
                                    const brandSelect = document.getElementById('brand_id');
                                    brands.forEach(brand => {
                                        if (brand.brand_id != data.brand_id) {
                                            brandSelect.innerHTML += `<option value="${brand.brand_id}">${brand.brand_name}</option>`;
                                        }
                                    });
                                });
                        });
                } else {
                    form.innerHTML = `
                            <label for="product_image">Hình Ảnh:</label>
                            <input id="product_image" class="form-control" placeholder="Nhập URL hình ảnh">

                            <label for="product_name">Tên Sản Phẩm:</label>
                            <input id="product_name" class="form-control" placeholder="Tên sản phẩm" required>

                            <label for="product_description">Mô Tả:</label>
                            <textarea id="product_description" placeholder="Mô tả" class="form-control" required></textarea>
                            
                            <label for="product_price">Giá:</label>
                            <input type="number" id="product_price" class="form-control" placeholder="Giá sản phẩm" required>
                            
                            <label for="product_stock">Số Lượng:</label>
                            <input type="number" id="product_stock" class="form-control" placeholder="Số lượng" required>
                            
                            <label for="category_id">Danh Mục:</label>
                            <select id="category_id" class="form-control" required>
                            </select>

                            <label for="brand_id">Thương Hiệu:</label>
                            <select id="brand_id" class="form-control" required></select>

                            <input type="hidden" id="product_id">
                            <button type="submit" class="btn" style="background-color: green; color: white;">Lưu</button>
                        `;
                    fetch('getCategories.php')
                        .then(response => response.json())
                        .then(categories => {
                            const categorySelect = document.getElementById('category_id');
                            categories.forEach(category => {
                                categorySelect.innerHTML += `<option value="${category.category_id}">${category.category_name}</option>`;
                            });
                        });

                    fetch('get_brands.php')
                        .then(response => response.json())
                        .then(brands => {
                            const brandSelect = document.getElementById('brand_id');
                            brands.forEach(brand => {
                                brandSelect.innerHTML += `<option value="${brand.brand_id}">${brand.brand_name}</option>`;
                            });
                        });
                }
                form.onsubmit = function (e) {
                    e.preventDefault();
                    const productName = document.getElementById('product_name').value;
                    const productImage = document.getElementById('product_image').value;
                    const productDescription = document.getElementById('product_description').value;
                    const productPrice = document.getElementById('product_price').value;
                    const productStock = document.getElementById('product_stock').value;
                    const productCategory = document.querySelector('#category_id').value;
                    const productBrand = document.querySelector('#brand_id').value;
                    const productId = document.getElementById('product_id') ? document.getElementById('product_id').value : null;
                    const formData = new FormData();
                    formData.append('product_name', productName);
                    formData.append('product_image', productImage);
                    formData.append('product_description', productDescription);
                    formData.append('product_price', productPrice);
                    formData.append('product_stock', productStock);
                    formData.append('category_id', productCategory);
                    formData.append('brand_id', productBrand);
                    if (productId) formData.append('product_id', productId);
                    fetch('save_product.php', {
                        method: 'POST',
                        body: formData
                    }).then(response => response.json())
                        .then(data => {
                            alert(data.message);
                            closeModal();
                            location.reload();
                        });
                };
            } else if (tyle === 'user') {
                title.textContent = 'Thêm Người Dùng';
                modal.style.display = 'block';
                form.innerHTML = `
            <label for="user_fullname">Họ tên:</label>
            <input type="text" id="user_fullname" placeholder="Họ tên" class="form-control" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" placeholder="Email" class="form-control" required>
            
            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" placeholder="Mật khẩu" class="form-control" required>
            
            <label for="phone_number">Số điện thoại:</label>
            <input type="tel" id="phone_number" placeholder="Số điện thoại" class="form-control">
            
            <label for="user_address">Địa chỉ:</label>
            <input type="text" id="user_address" placeholder="Địa chỉ" class="form-control">
            
            <label for="user_gender">Giới tính:</label>
            <select id="user_gender" class="form-control">
                <option value="MALE">Nam</option>
                <option value="FEMALE">Nữ</option>
                <option value="OTHER">Khác</option>
            </select>
            
            <label for="role">Vai trò:</label>
            <select id="role" class="form-control">
                <option value="CUSTOMER">CUSTOMER</option>
                <option value="ADMIN">ADMIN</option>
            </select>
            
            <button type="submit" class="btn btn-primary mt-3">Lưu</button>
        `;
                form.onsubmit = function (e) {
                    e.preventDefault();
                    const formData = new FormData();
                    formData.append('user_fullname', document.getElementById('user_fullname').value);
                    formData.append('email', document.getElementById('email').value);
                    formData.append('password', document.getElementById('password').value);
                    formData.append('phone_number', document.getElementById('phone_number').value);
                    formData.append('user_address', document.getElementById('user_address').value);
                    formData.append('user_gender', document.getElementById('user_gender').value);
                    formData.append('role', document.getElementById('role').value);

                    fetch('save_user.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            alert(data.message);
                            if (data.status === 'success') {
                                closeModal();
                                loadUsers();
                            }
                        });
                };
            } else if (tyle === 'category') {
                title.textContent = 'Thêm Danh Mục';
                modal.style.display = 'block';
                form.innerHTML = `
                <label for="category_name">Tên danh mục: </label>
                <input type="text" id="category_name" placeholder="Danh mục" class="form-control" required>
                
                <label for="category_description">Mô tả danh mục:</label>
                <input type="text" id="category_description" placeholder="Mô tả danh mục" class="form-control" required>

                <button type="submit" class="btn btn-primary mt-3">Lưu</button>
                `;
                form.onsubmit = function (e) {
                    e.preventDefault();
                    const formData = new FormData();
                    formData.append('category_name', document.getElementById('category_name').value);
                    formData.append('category_description', document.getElementById('category_description').value);

                    fetch('save_category.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            alert(data.message);
                            if (data.status === 'success') {
                                closeModal();
                                loadUsers();
                                location.reload();
                            }
                        });
                };
            } else if (tyle === 'brand') {
                title.textContent = 'Thêm Thương Hiệu';
                modal.style.display = 'block';
                form.innerHTML = `
                <label for="brand_name">Thương hiệu: </label>
                <input type="text" id="brand_name" placeholder="Thương hiệu" class="form-control" required>
                
                <label for="brand_description">Mô tả thương hiệu: </label>
                <input type="text" id="brand_description" placeholder="Mô tả thương hiệu" class="form-control" required>
               
                <button type="submit" class="btn btn-primary mt-3">Lưu</button>
                `;
                form.onsubmit = function (e) {
                    e.preventDefault();
                    const formData = new FormData();
                    formData.append('brand_name', document.getElementById('brand_name').value);
                    formData.append('brand_description', document.getElementById('brand_description').value);

                    fetch('save_brand.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            alert(data.message);
                            if (data.status === 'success') {
                                closeModal();
                                loadUsers();
                                location.reload();
                            }
                        });
                };
            }
        }

        function closeModal() {
            const modal = document.getElementById('admin-modal');
            modal.style.display = 'none';
        }

        function deleteProduct(product_id) {
            if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này?")) {
                fetch('delete_product.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${product_id}`,
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message);
                            // Xóa dòng sản phẩm trong bảng
                            const row = document.querySelector(`#product-list tr[data-product-id="${product_id}"]`);
                            if (row) row.remove();
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Có lỗi xảy ra:', error);
                        alert('Không thể xóa sản phẩm. Vui lòng thử lại.');
                    });
            }
        }

        function loadCategories() {
            fetch('getCategories.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Lỗi khi lấy dữ liệu từ API');
                    }
                    return response.json();
                })
                .then(categories => {
                    const categoryList = document.getElementById('category-list');
                    categoryList.innerHTML = ''; // Xóa dữ liệu cũ trước khi thêm mới

                    if (categories.length > 0) {
                        categories.forEach(category => {
                            const row = `
                                <tr>
                                    <td>${category.category_id}</td>
                                    <td>${category.category_name}</td>
                                    <td>${category.category_description}</td>
                                    <td>
                                        <button class='btn btn-warning' onclick="editCategory(${category.category_id})">Sửa</button>
                                        <button class='btn btn-danger' onclick="deleteCategory(${category.category_id})">Xóa</button>
                                    </td>
                                </tr>`;
                            categoryList.insertAdjacentHTML('beforeend', row);
                        });
                    } else {
                        categoryList.innerHTML = '<tr><td colspan="4">Không có danh mục nào.</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    document.getElementById('category-list').innerHTML = '<tr><td colspan="4">Lỗi khi tải danh mục.</td></tr>';
                });
        }
        // Gọi hàm loadCategories() khi tab được hiển thị
        document.querySelector('.tab-button[onclick="showTab(\'categories\')"]').addEventListener('click', loadCategories);

        function loadBrands() {
            fetch('getBrands.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Lỗi khi lấy dữ liệu từ API');
                    }
                    return response.json();
                })
                .then(brands => {
                    const brandList = document.getElementById('brand-list');
                    brandList.innerHTML = ''; // Xóa dữ liệu cũ trước khi thêm mới

                    if (brands.length > 0) {
                        brands.forEach(brand => {
                            const row = `
                                <tr>
                                    <td>${brand.brand_id}</td>
                                    <td>${brand.brand_name}</td>
                                    <td>${brand.brand_description}</td>
                                    <td>
                                        <button class='btn btn-warning' onclick="editBrand(${brand.brand_id})">Sửa</button>
                                        <button class='btn btn-danger' onclick="deleteBrand(${brand.brand_id})">Xóa</button>
                                    </td>
                                </tr>`;
                            brandList.insertAdjacentHTML('beforeend', row);
                        });
                    } else {
                        brandList.innerHTML = '<tr><td colspan="4">Không có thương hiệu nào.</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    document.getElementById('brand-list').innerHTML = '<tr><td colspan="4">Lỗi khi tải thương hiệu.</td></tr>';
                });
        }

        // Gọi hàm loadBrands() khi tab được hiển thị
        document.querySelector('.tab-button[onclick="showTab(\'brands\')"]').addEventListener('click', loadBrands);

        function loadUsers() {
            fetch('getUsers.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Lỗi khi lấy dữ liệu từ API');
                    }
                    return response.json();
                })
                .then(users => {
                    const userList = document.getElementById('user-list');
                    userList.innerHTML = ''; // Xóa dữ liệu cũ trước khi thêm mới

                    if (users.length > 0) {
                        users.forEach(user => {
                            const row = `
                                <tr>
                                    <td>${user.user_id}</td>
                                    <td>${user.user_fullname}</td>
                                    <td>${user.email}</td>
                                    <td>${user.phone_number || 'Không có'}</td>
                                    <td>${user.user_address || 'Không có'}</td>
                                    <td>${user.user_gender || 'Không xác định'}</td>
                                    <td>${user.role}</td>
                                    <td>
                                        <button class='btn btn-warning' onclick="editUser(${user.user_id})">Sửa</button>
                                        
                                    </td>
                                </tr>`;
                            userList.insertAdjacentHTML('beforeend', row);
                        });
                    } else {
                        userList.innerHTML = '<tr><td colspan="8">Không có người dùng nào.</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    document.getElementById('user-list').innerHTML = '<tr><td colspan="8">Lỗi khi tải người dùng.</td></tr>';
                });
        }

        // Gọi hàm loadUsers() khi tab được hiển thị
        document.querySelector('.tab-button[onclick="showTab(\'users\')"]').addEventListener('click', loadUsers);

        function loadOrders() {
            fetch('getOrders.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Lỗi khi lấy dữ liệu từ API');
                    }
                    return response.json();
                })
                .then(orders => {
                    const orderList = document.getElementById('order-list');
                    orderList.innerHTML = ''; // Xóa dữ liệu cũ trước khi thêm mới

                    if (orders.length > 0) {
                        orders.forEach(order => {
                            const row = `
                                <tr>
                                    <td>${order.order_id}</td>
                                    <td>${order.user_id}</td>
                                    <td>${order.order_date}</td>
                                    <td>${order.total_amount}</td>
                                    <td>${order.order_status}</td>
                                    <td>${order.payment_method_id}</td>
                                    <td>${order.payment_date || 'Chưa thanh toán'}</td>
                                    <td>${order.payment_status}</td>
                                    <td>${order.shipment_address}</td>
                                    <td>${order.delivery_date || 'Chưa giao'}</td>
                                    <td>${order.shipment_status}</td>
                                    
                                </tr>`;
                            orderList.insertAdjacentHTML('beforeend', row);
                        });
                    } else {
                        orderList.innerHTML = '<tr><td colspan="12">Không có đơn hàng nào.</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    document.getElementById('order-list').innerHTML = '<tr><td colspan="12">Lỗi khi tải đơn hàng.</td></tr>';
                });
        }

        // Gọi hàm loadOrders() khi tab được hiển thị
        document.querySelector('.tab-button[onclick="showTab(\'orders\')"]').addEventListener('click', loadOrders);

        // CRUD User fuctions
        function editUser(user_id) {
            fetch(`get_user.php?user_id=${user_id}`)
                .then(response => response.json())
                .then(user => {
                    const modal = document.getElementById('admin-modal');
                    const form = document.getElementById('admin-form');
                    const title = document.getElementById('modal-title');

                    title.textContent = 'Sửa Người Dùng';
                    modal.style.display = 'block';

                    form.innerHTML = `
                <input type="hidden" id="user_id" value="${user.user_id}">
                <label for="user_fullname">Họ tên:</label>
                <input type="text" id="user_fullname" value="${user.user_fullname}" placeholder="Họ tên" class="form-control" readonly >
                
                <label for="email">Email:</label>
                <input type="email" id="email" value="${user.email}" placeholder="Email" class="form-control" readonly>
                
                <label for="phone_number">Số điện thoại:</label>
                <input type="tel" id="phone_number" value="${user.phone_number || ''}" placeholder="Số điện thoại" class="form-control" readonly>
                
                <label for="user_address">Địa chỉ:</label>
                <input type="text" id="user_address" value="${user.user_address || ''}" placeholder="Địa chỉ" class="form-control" readonly>
                
                <label for="user_gender">Giới tính:</label>
                <input type="text" id="user_gender" value="${user.user_gender || ''}" placeholder="Giới Tính" class="form-control" readonly>
                
                <label for="role">Vai trò:</label>
                <select id="role" class="form-control">
                    <option value="ADMIN" ${user.role === 'ADMIN' ? 'selected' : ''}>ADMIN</option>
                    <option value="CUSTOMER" ${user.role === 'CUSTOMER' ? 'selected' : ''}>CUSTOMER</option>
                </select>
                
                <button type="submit" class="btn btn-primary mt-3">Lưu</button>
            `;

                    form.onsubmit = function (e) {
                        e.preventDefault();
                        const formData = new FormData();
                        formData.append('user_id', document.getElementById('user_id').value);
                        formData.append('user_fullname', document.getElementById('user_fullname').value);
                        formData.append('email', document.getElementById('email').value);
                        formData.append('phone_number', document.getElementById('phone_number').value);
                        formData.append('user_address', document.getElementById('user_address').value);
                        formData.append('user_gender', document.getElementById('user_gender').value);
                        formData.append('role', document.getElementById('role').value);

                        fetch('save_user.php', {
                            method: 'POST',
                            body: formData
                        })
                            .then(response => response.json())
                            .then(data => {
                                alert(data.message);
                                if (data.status === 'success') {
                                    closeModal();
                                    loadUsers();
                                }
                            });
                    };
                });
        }

        function deleteUser(userId) {
            if (confirm("Bạn có chắc chắn muốn xóa người dùng này?")) {
                const formData = new FormData();
                formData.append('user_id', userId);

                fetch('delete_user.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.status === 'success') {
                            loadUsers();
                        }
                    })
                    .catch(error => {
                        alert(data.message);
                        if (data.status === 'error') {
                            location.reload();
                        }
                    });
            }
        }

        // CRUD categories
        function editCategory(category_id) {
            fetch(`edit_categories.php?category_id=${category_id}`)
                .then(response => response.json())
                .then(categories => {
                    const modal = document.getElementById('admin-modal');
                    const form = document.getElementById('admin-form');
                    const title = document.getElementById('modal-title');

                    title.textContent = 'Sửa Danh Mục';
                    modal.style.display = 'block';

                    form.innerHTML = `
                <input type="hidden" id="category_id" value="${categories.category_id}">
                <label for="category_name">Danh mục: </label>
                <input type="text" id="category_name" value="${categories.category_name}" placeholder="Danh mục" class="form-control" required>
                
                <label for="category_description"Mô tả danh mục: </label>
                <input type="text" id="category_description" value="${categories.category_description}" placeholder="Mô tả danh mục" class="form-control" required>
                
                <button type="submit" class="btn btn-primary mt-3">Lưu</button>
            `;

                    form.onsubmit = function (e) {
                        e.preventDefault();
                        const formData = new FormData();
                        formData.append('category_id', document.getElementById('category_id').value);
                        formData.append('category_name', document.getElementById('category_name').value);
                        formData.append('category_description', document.getElementById('category_description').value);

                        fetch('save_category.php', {
                            method: 'POST',
                            body: formData
                        })
                            .then(response => response.json())
                            .then(data => {
                                alert(data.message);
                                if (data.status === 'success') {
                                    closeModal();
                                    loadUsers();
                                    location.reload();
                                }
                            });
                    };
                });
        }

        function deleteCategory(category_id) {
            if (confirm("Bạn có chắc chắn muốn xóa danh mục này?")) {
                fetch('delete_category.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `category_id=${category_id}`,
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message);
                            const row = document.querySelector(`#product-list tr[data-product-id="${category_id}"]`);
                            if (row) row.remove();
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Có lỗi xảy ra:', error);
                        alert('Không thể xóa sản phẩm. Vui lòng thử lại.');
                    });
            }
        }
        // CRUD brands
        function editBrand(brand_id) {
            fetch(`edit_brands.php?brand_id=${brand_id}`)
                .then(response => response.json())
                .then(brands => {
                    const modal = document.getElementById('admin-modal');
                    const form = document.getElementById('admin-form');
                    const title = document.getElementById('modal-title');

                    title.textContent = 'Sửa Thương Hiệu';
                    modal.style.display = 'block';

                    form.innerHTML = `
                <input type="hidden" id="brand_id" value="${brands.brand_id}">
                <label for="brand_name">Thương hiệu: </label>
                <input type="text" id="brand_name" value="${brands.brand_name}" placeholder="Thương hiệu" class="form-control" required>
                
                <label for="brand_description"Mô tả danh mục: </label>
                <input type="text" id="brand_description" value="${brands.brand_description}" placeholder="Mô tả thương hiệu" class="form-control" required>
                
                <button type="submit" class="btn btn-primary mt-3">Lưu</button>
            `;

                    form.onsubmit = function (e) {
                        e.preventDefault();
                        const formData = new FormData();
                        formData.append('brand_id', document.getElementById('brand_id').value);
                        formData.append('brand_name', document.getElementById('brand_name').value);
                        formData.append('brand_description', document.getElementById('brand_description').value);

                        fetch('save_brand.php', {
                            method: 'POST',
                            body: formData
                        })
                            .then(response => response.json())
                            .then(data => {
                                alert(data.message);
                                if (data.status === 'success') {
                                    closeModal();
                                    loadUsers();
                                    location.reload();
                                }
                            });
                    };
                });
        }

        function deleteBrand(brand_id) {
            if (confirm("Bạn có chắc chắn muốn xóa thương hiệu này?")) {
                fetch('delete_brand.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `brand_id=${brand_id}`,
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message);
                            const row = document.querySelector(`#product-list tr[data-product-id="${brand_id}"]`);
                            if (row) row.remove();
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Có lỗi xảy ra:', error);
                        alert('Không thể xóa sản phẩm. Vui lòng thử lại.');
                    });
            }
        }
        
    </script>
    <div class="footer">
        <footer class="text-center text-lg-start bg-body-tertiary text-muted">
            <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
                <div class="me-5 d-none d-lg-block">
                    <span>Kết Nối Với Chúng Tôi Thông Qua Mạng Xã Hội</span>
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
                                <i class="fas fa-gem me-3"></i>Tên Công Ty
                            </h6>
                            <p>GGSHOP COMPANY</p>
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                            <h6 class="text-uppercase fw-bold mb-4">Sản Phẩm</h6>
                            <p><a href="#!" class="text-reset">Bàn Phím</a></p>
                            <p><a href="#!" class="text-reset">Chuột</a></p>
                            <p><a href="#!" class="text-reset">Tai Nghe</a></p>
                            <p><a href="#!" class="text-reset">Các Sản Phẩm Khác</a></p>
                        </div>
                        <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                            <h6 class="text-uppercase fw-bold mb-4">Hỗ Trợ</h6>
                            <p><a href="#!" class="text-reset">Thanh Toán</a></p>
                            <p><a href="#!" class="text-reset">Cài Đặt</a></p>
                            <p><a href="#!" class="text-reset">Đặt Hàng</a></p>
                            <p><a href="#!" class="text-reset">Hỗ Trợ</a></p>
                        </div>
                        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                            <h6 class="text-uppercase fw-bold mb-4">Liên Lạc</h6>
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