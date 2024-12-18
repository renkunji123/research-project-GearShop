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
        <a href="/" class="d-flex align-items-center text-dark text-decoration-none me-4">
            <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                <use xlink:href="#bootstrap"></use>
            </svg>
        </a>
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
                    <a href="cart.php" class="btn btn-info d-flex align-items-center position-relative">
                        <i class="bi bi-cart"></i>
                        <span id="cart-count" class="badge bg-danger position-absolute top-0 start-100 translate-middle badge rounded-pill">0 <!-- Đây là giá trị mặc định -->
                        </span>
                    </a>
                    <?php if (isset($_SESSION['user'])): ?>
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
                                            <i class="bi bi-shield-lock"></i> Quản Trị Viên
                                        </a></li>
                                <?php endif; ?>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="logout.php"><i class="bi bi-door-open"></i>Đăng Xuất</a>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <button type="button" class="btn btn-outline-primary"
                            onclick="window.location.href='login.php';">
                            <i class="bi bi-box-arrow-in-right"></i> Đăng Nhập
                        </button>
                        <button type="button" class="btn btn-primary"
                            onclick="window.location.href='register.php';">
                            <i class="bi bi-person-plus"></i> Đăng Ký
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
        <div>
            <?php require_once("../research-project-GearShop/vnpay_php/config.php"); ?>
            <div class="container">
                <div class="table-responsive">
                    <form action="../research-project-GearShop/vnpay_php/vnpay_create_payment.php" id="frmCreateOrder" method="post">
                        <div class="container mt-4">
                            <div class="row">
                                <div class="col-md-6 text-start">
                                    <a href="../research-project-GearShop/homepage.php" class="btn btn-outline-primary btn-lg">Tiếp tục mua sắm</a>
                                </div>
                                <div class="col-md-6 text-end">
                                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['user']['id']) ?>" />
                                    <div class="input-group mb-3 ">
                                        <span style="font-size: 20px;" class="fw-bold input-group-text">Tổng Tiền</span>
                                        <input class="form-control fw-bold" style="font-size: 20px;" data-val="true" data-val-number="The field Amount must be a number." data-val-required="The Amount field is required." id="amount" max="100000000" min="1" name="amount" type="number" readonly />
                                        <span style="font-size: 20px;" class="fw-bold input-group-text">VND</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <h5 class="fw-bold">Cách 1: Chuyển hướng sang Cổng VNPAY chọn phương thức thanh toán</h5>
                                <div class="form-check">
                                    <input type="radio" checked="true" id="bankCode1" name="bankCode" class="form-check-input" value="">
                                    <label for="bankCode1" class="form-check-label">Cổng thanh toán VNPAYQR</label>
                                </div>

                                <h5 class="fw-bold mt-3">Cách 2: Tách phương thức tại site của đơn vị kết nối</h5>
                                <div class="form-check">
                                    <input type="radio" id="bankCode2" name="bankCode" class="form-check-input" value="VNPAYQR">
                                    <label for="bankCode2" class="form-check-label">Thanh toán bằng ứng dụng hỗ trợ VNPAYQR</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="bankCode3" name="bankCode" class="form-check-input" value="VNBANK">
                                    <label for="bankCode3" class="form-check-label">Thanh toán qua thẻ ATM/Tài khoản nội địa</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="bankCode4" name="bankCode" class="form-check-input" value="INTCARD">
                                    <label for="bankCode4" class="form-check-label">Thanh toán qua thẻ quốc tế</label>
                                </div>
                            </div>
                            <div class="form-group mb-3" hidden>
                                <h5 class="fw-bold">Chọn ngôn ngữ giao diện thanh toán:</h5>
                                <div class="form-check">
                                    <input type="radio" checked="true" id="language-vn" name="language" class="form-check-input" value="vn">
                                    <label for="language-vn" class="form-check-label">Tiếng Việt</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="language-en" name="language" class="form-check-input" value="en">
                                    <label for="language-en" class="form-check-label">Tiếng Anh</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 text-start">
                                    <button type="submit" id="payOnline" class=" btn btn-primary btn-lg  ">Thanh toán Online</button>
                                </div>
                                <div class="col-md-4 text-center mt-3">
                                    <p class="fw-bold">Hoặc</p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <button type="submit" class=" btn btn-success btn-lg ">Thanh toán COD</button>
                                </div>
                            </div>
                        </div>

                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    <div class="footer">
        <footer class="text-center text-lg-start bg-body-tertiary text-muted">
            <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
                <div class="me-5 d-none d-lg-block">
                    <span>Kết Nối Với Chúng Tôi Thông Qua Mạng Xã Hội:</span>
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
                            <h6 class="text-uppercase fw-bold mb-4"><i class="fas fa-gem me-3"></i>Company Name</h6>
                            <p>GGSHOP COMPANY</p>
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                            <h6 class="text-uppercase fw-bold mb-4">Sản Phẩm</h6>
                            <p><a href="#!" class="text-reset">Bàn Phím</a></p>
                            <p><a href="#!" class="text-reset">Chuột</a></p>
                            <p><a href="#!" class="text-reset">Tai Nghe</a></p>
                            <p><a href="#!" class="text-reset">Và Các Sản Phẩm Khác</a></p>
                        </div>
                        <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                            <h6 class="text-uppercase fw-bold mb-4">Liên Kết Hỗ Trợ</h6>
                            <p><a href="#!" class="text-reset">Thanh Toán</a></p>
                            <p><a href="#!" class="text-reset">Cài Đặt</a></p>
                            <p><a href="#!" class="text-reset">Đặt Hàng</a>
                            <p><a href="#!" class="text-reset">Hỗ Trợ</a>
                            </p>
                        </div>
                        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                            <h6 class="text-uppercase fw-bold mb-4">Liên Lạc</h6>
                            <p><i class="fas fa-home me-3"></i> F601 F Tower</p>
                            <p><i class="fas fa-envelope me-3"></i>info@example.com</p>
                            <p><i class="fas fa-phone me-3"></i> + 01 234 567 88</p>
                            <p><i class="fas fa-print me-3"></i> + 01 234 567 89</p>
                        </div>
                    </div>
                </div>
            </section>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    fetch('get_cart_count.php') // Đảm bảo file PHP này trả về số lượng giỏ hàng
                        .then(response => response.json())
                        .then(data => {
                            const cartCountElement = document.getElementById('cart-count');
                            cartCountElement.textContent = data.totalItems;
                        })
                        .catch(error => console.error('Error fetching cart count:', error));
                });
                document.addEventListener("DOMContentLoaded", function() {
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
                                document.getElementById('amount').value = total;
                            }
                        })
                        .catch(error => {
                            console.error('Lỗi:', error);
                            document.getElementById('cart-items').innerHTML = `<p>Lỗi khi tải dữ liệu giỏ hàng.</p>`;
                        });
                });

                function updateCart(productId, newQuantity) {
                    console.log(`Cập nhật sản phẩm ${productId} với số lượng mới: ${newQuantity}`);
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
                const userId = "<?php echo isset($_SESSION['id']) ? $_SESSION['id'] : ''; ?>";
                const address = "<?php echo isset($_SESSION['address']) ? $_SESSION['address'] : ''; ?>";

                const currentDate = new Date(); // Lấy ngày hiện tại
                // Format ngày thành chuỗi (ví dụ: YYYY-MM-DD)
                const formattedDate = currentDate.toISOString().split('T')[0];
                document.getElementById('payOnline').addEventListener('click', function(event) {
                    e.preventDefault();
                    const formData = new FormData();
                    formData.append('user_id', userId);
                    formData.append('order_date', formattedDate);
                    formData.append('total_amount', document.getElementById('amount'));
                    formData.append('order_status', 'COMPLETED');
                    formData.append('payment_method_id', '1');
                    formData.append('payment_date', formattedDate);
                    formData.append('payment_status', 'COMPLETED');
                    formData.append('shipment_address', address);
                    formData.append('shipment_status', 'IN_TRANSIT');

                    if (amount > 0) {
                        fetch('add_order.php'), {
                                method: 'POST',
                                body: formData
                            }
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    alert(data.message);
                                    window.location.href = ''; // Điều hướng tới trang "Cảm ơn"
                                } else {
                                    alert(data.message);
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    } else {
                        alert('Số tiền không hợp lệ.');
                    }
                });
            </script>
</body>

</html>