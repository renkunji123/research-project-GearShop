<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <header class="d-flex align-items-center justify-content-between py-3 px-4 border-bottom">
        <!-- Logo -->
        <a href="/" class="d-flex align-items-center text-dark text-decoration-none me-4">
            <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                <use xlink:href="#bootstrap"></use>
            </svg>
        </a>

        <!-- Danh sách liên kết điều hướng -->
        <ul class="nav me-auto">
            <li><a href="homepage.php" class="nav-link px-2 link-secondary">Home</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">Features</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">Pricing</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">FAQs</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">About</a></li>
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
                        <span class="ms-2 d-none d-md-inline">Cart</span>
                        <!-- Hiển thị số lượng sản phẩm trong giỏ hàng -->
                        <span id="cart-count" class="badge bg-danger position-absolute top-0 start-100 translate-middle badge rounded-pill">
                            0 <!-- Đây là giá trị mặc định -->
                        </span>
                    </a>
                    <!-- Kiểm tra trạng thái đăng nhập -->
                    <?php if (isset($_SESSION['user'])): ?>
                        <!-- Nếu người dùng đã đăng nhập -->
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle"
                                type="button" id="userDropdown" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="bi bi-person-circle "></i>
                                <?= htmlspecialchars($_SESSION['user']['name']) ?>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="profile.php">
                                        <i class="bi bi-person-fill"></i>
                                        Profile
                                    </a></li>
                                <?php if ($_SESSION['user']['role'] === 'ADMIN'): ?>
                                    <li><a class="dropdown-item" href="adminPage.php">
                                            <i class="bi bi-shield-lock"></i> Admin
                                        </a></li>
                                <?php endif; ?>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="logout.php">
                                        <i class="bi bi-door-open"></i>
                                        Logout
                                    </a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <!-- Nếu người dùng chưa đăng nhập -->
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

    <div class="container my-5">
        <h2 class="mb-4">Giỏ hàng của bạn</h2>

        <div class="cart-items bg-light p-3 rounded" id="cart-items">

        </div>



        <!-- Tổng cộng -->
        <div class="row mt-4">
            <div class="col-md-6">
                <a href="/Frontend/home_page.html" class="btn btn-outline-primary">Tiếp tục mua sắm</a>
            </div>
            <div class="col-md-6 text-end">
                <h4>
                    <div class="fw-bold" id="totalPrice">Tổng tiền: 0 VND</div>
                </h4>
                <button class="btn btn-primary btn-lg mt-3">Thanh toán</button>
            </div>

        </div>
    </div>



    <div class="footer">
        <!-- Footer -->
        <footer class="text-center text-lg-start bg-body-tertiary text-muted">
            <!-- Section: Social media -->
            <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
                <!-- Left -->
                <div class="me-5 d-none d-lg-block">
                    <span>Get connected with us on social networks:</span>
                </div>
                <!-- Left -->
                <div></div>
                <!-- Right -->
                <div>
                    <a href="" class="me-4 text-reset">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="" class="me-4 text-reset">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="" class="me-4 text-reset">
                        <i class="fab fa-google"></i>
                    </a>
                    <a href="" class="me-4 text-reset">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="" class="me-4 text-reset">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="" class="me-4 text-reset">
                        <i class="fab fa-github"></i>
                    </a>
                </div>
                <!-- Right -->
            </section>
            <!-- Section: Social media -->

            <!-- Section: Links  -->
            <section class="">
                <div class="container text-center text-md-start mt-5">
                    <!-- Grid row -->
                    <div class="row mt-3">
                        <!-- Grid column -->
                        <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                            <!-- Content -->
                            <h6 class="text-uppercase fw-bold mb-4">
                                <i class="fas fa-gem me-3"></i>Company name
                            </h6>
                            <p>
                                Here you can use rows and columns to organize your footer content. Lorem ipsum
                                dolor sit amet, consectetur adipisicing elit.
                            </p>
                        </div>
                        <!-- Grid column -->

                        <!-- Grid column -->
                        <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                            <!-- Links -->
                            <h6 class="text-uppercase fw-bold mb-4">
                                Products
                            </h6>
                            <p>
                                <a href="#!" class="text-reset">Angular</a>
                            </p>
                            <p>
                                <a href="#!" class="text-reset">React</a>
                            </p>
                            <p>
                                <a href="#!" class="text-reset">Vue</a>
                            </p>
                            <p>
                                <a href="#!" class="text-reset">Laravel</a>
                            </p>
                        </div>
                        <!-- Grid column -->

                        <!-- Grid column -->
                        <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                            <!-- Links -->
                            <h6 class="text-uppercase fw-bold mb-4">
                                Useful links
                            </h6>
                            <p>
                                <a href="#!" class="text-reset">Pricing</a>
                            </p>
                            <p>
                                <a href="#!" class="text-reset">Settings</a>
                            </p>
                            <p>
                                <a href="#!" class="text-reset">Orders</a>
                            </p>
                            <p>
                                <a href="#!" class="text-reset">Help</a>
                            </p>
                        </div>

                        <!-- Grid column -->
                        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                            <!-- Links -->
                            <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
                            <p><i class="fas fa-home me-3"></i> New York, NY 10012, US</p>
                            <p>
                                <i class="fas fa-envelope me-3"></i>
                                info@example.com
                            </p>
                            <p><i class="fas fa-phone me-3"></i> + 01 234 567 88</p>
                            <p><i class="fas fa-print me-3"></i> + 01 234 567 89</p>
                        </div>
                        <!-- Grid column -->
                    </div>
                    <!-- Grid row -->
                </div>
            </section>
            <!-- chatbot -->

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // Gửi yêu cầu lấy số lượng sản phẩm trong giỏ hàng
                    fetch('get_cart_count.php') // Đảm bảo file PHP này trả về số lượng giỏ hàng
                        .then(response => response.json())
                        .then(data => {
                            // Cập nhật số lượng sản phẩm vào phần tử hiển thị trên nút cart
                            const cartCountElement = document.getElementById('cart-count');
                            cartCountElement.textContent = data.totalItems;
                        })
                        .catch(error => console.error('Error fetching cart count:', error));
                });
                document.addEventListener("DOMContentLoaded", function() {
                    // Gửi yêu cầu đến get_cart.php để lấy dữ liệu giỏ hàng
                    fetch('get_cart.php')
                        .then(response => response.json())
                        .then(data => {
                            const cartContainer = document.getElementById('cart-items');
                            cartContainer.innerHTML = ''; // Xóa nội dung cũ

                            if (data.error) {
                                cartContainer.innerHTML = `<p>${data.error}</p>`;
                            } else if (data.length === 0) {
                                cartContainer.innerHTML = `<p>Giỏ hàng của bạn trống.</p>`;
                            } else {
                                let total = 0;
                                let cartId = null;
                                data.forEach(item => {
                                    if (!cartId) cartId = item.cart_id;
                                    total += item.cart_item_quantity * item.product_price;

                                    cartContainer.innerHTML += `
                        <div class="row align-items-center mb-4 border-bottom pb-3">
                            <div class="col-2">
                                <img src="${item.product_image}" alt="${item.product_name}" class="img-fluid rounded shadow-sm">
                            </div>
                            <div class="col-4">
                                <h5 class="mb-1 fw-bold">${item.product_name}</h5>
                            </div>
                            <div class="col-2">
                                <span class="price text-success fw-bold fs-5">${item.product_price} $</span>
                            </div>
                            <div class="col-2">
                                <input type="number" class="form-control mx-2 text-center" value="${item.cart_item_quantity}" min="1" onchange="updateCart(${item.product_id}, this.value)">
                            </div>
                            <div class="col-2 text-end">
                                <button class="btn btn-danger btn-sm" onclick="removeFromCart(${item.product_id},${item.cart_id})">Xóa</button>
                            </div>
                        </div>`;
                                });

                                // Cập nhật tổng tiền
                                document.getElementById('totalPrice').innerHTML = `Tổng tiền: ${total.toLocaleString()} $`;
                            }
                        })
                        .catch(error => {
                            console.error('Lỗi:', error);
                            document.getElementById('cart-items').innerHTML = `<p>Lỗi khi tải dữ liệu giỏ hàng.</p>`;
                        });
                });

                // Cập nhật số lượng trong giỏ hàng
                function updateCart(productId, newQuantity) {
                    console.log(`Cập nhật sản phẩm ${productId} với số lượng mới: ${newQuantity}`);
                    // Gửi yêu cầu cập nhật lên server (API cần triển khai thêm nếu chưa có)
                }

                function removeFromCart(productId, cartId) {
                    console.log(`Xóa sản phẩm ${productId} khỏi giỏ hàng`);
                    console.log(`Xóa giỏ hàng với cartId ${cartId}`);

                    fetch('remove_from_cart.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `product_id=${productId}&cart_id=${cartId}`,
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                // Cập nhật lại giao diện, có thể gọi lại hàm loadCart()
                                loadCart();
                                location.reload();
                            } else {
                                alert(data.error || 'Đã xóa thành công');
                                location.reload();
                            }
                        })
                        .catch(error => {
                            console.error('Lỗi:', error);
                            alert('Đã xóa thành công');
                            location.reload();
                        });
                }
                
            </script>

</body>

</html>