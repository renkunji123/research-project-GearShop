<?php
session_start();
$isLoggedIn = isset($_SESSION['user']);
$userName = $isLoggedIn ? $_SESSION['user']['name'] : null;
$userRole = $isLoggedIn ? $_SESSION['user']['role'] : null;
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script>
        // Fetch product hotdeal
        fetch('fetch_products.php') // Chú ý sử dụng đường dẫn thích hợp
            .then(response => response.json())
            .then(data => {
                let hotDealProductsContainer = document.querySelector('.product-wrapper');
                data.forEach(product => {
                    let productCard = `
                <div class="product-card" role="article" aria-label="Product Item">
                    <div class="card h-100">
                        <img src="${product.product_image}" class="card-img-top" alt="${product.product_name}">
                        <div >
                            <a href="product_view.php?product_id=${product.product_id}" class="btn quick-view btn-light">Xem</a>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">${product.product_name}</h5>
                            <p class="card-text">${product.product_price} VND</p>
                            <button class="btn btn-primary add-to-cart" 
                                data-product-id="${product.product_id}"
                                data-unit-price="${product.product_price}"
                                aria-label="Add to Cart"> ➕🛒</button>
                        </div>
                    </div>
                </div>
            `;
                    hotDealProductsContainer.innerHTML += productCard;
                });

                // Thêm sự kiện cho nút "Add to Cart"
                document.querySelectorAll('.add-to-cart').forEach(button => {
                    // Đảm bảo mỗi nút chỉ có sự kiện click một lần
                    if (!button.hasEventListener) { // Nếu sự kiện chưa được thêm
                        button.addEventListener('click', function(event) {
                            // Thêm thuộc tính kiểm tra cho button
                            button.hasEventListener = true;

                            const productId = this.getAttribute('data-product-id');
                            const unitPrice = this.getAttribute('data-unit-price');
                            const quantity = 1; // Giả sử mỗi lần thêm chỉ có 1 sản phẩm

                            // Gửi yêu cầu thêm sản phẩm vào giỏ hàng
                            fetch('add_to_cart.php', {
                                    method: 'POST',
                                    body: new URLSearchParams({
                                        'product_id': productId,
                                        'quantity': quantity,
                                        'unit_price': unitPrice
                                    })
                                })
                                .then(response => response.text())
                                .then(data => {
                                    alert(data);
                                    loadCount(); // Hiển thị thông báo
                                })
                                .catch(error => console.error('Error:', error));
                        });
                    }
                });
            })


        fetch('fetch_products.php') // Đảm bảo rằng đường dẫn đúng với cấu trúc thư mục của bạn
            .then(response => response.json())
            .then(data => {
                let featureProductContainer = document.querySelector('.product-container');
                data.forEach(product => {
                    let productCard = `
                    <div class="product-card">
                        <div class="custom-card">
                            <img src="${product.product_image}" class="card-img-top" alt="${product.product_name}">
                             <div >
                                <a href="product_view.php?product_id=${product.product_id}" class="btn quick-view btn-light">Xem</a>
                            </div>
                            <div class="custom-card-body">
                                <h5 class="card-title">${product.product_name}</h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="custom-price">${product.product_price} VND</span>
                                    <button class="btn btn-primary add-to-cart1" data-product-id="${product.product_id}"
                                data-unit-price="${product.product_price}">➕🛒</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                    featureProductContainer.innerHTML += productCard; // Chèn card vào container
                });
                document.querySelectorAll('.add-to-cart1').forEach(button => {
                    if (!button.hasEventListener) { // Nếu sự kiện chưa được thêm
                        button.addEventListener('click', function(event) {
                            // Thêm thuộc tính kiểm tra cho button
                            button.hasEventListener = true;

                            const productId = this.getAttribute('data-product-id');
                            const unitPrice = this.getAttribute('data-unit-price');
                            const quantity = 1; // Giả sử mỗi lần thêm chỉ có 1 sản phẩm

                            // Gửi yêu cầu thêm sản phẩm vào giỏ hàng
                            fetch('add_to_cart.php', {
                                    method: 'POST',
                                    body: new URLSearchParams({
                                        'product_id': productId,
                                        'quantity': quantity,
                                        'unit_price': unitPrice
                                    })
                                })
                                .then(response => response.text())
                                .then(data => {
                                    alert(data); // Hiển thị thông báo
                                })
                                .catch(error => console.error('Error:', error));
                        });
                    }
                });
            })
            .catch(error => {
                console.error('Có lỗi xảy ra khi lấy dữ liệu:', error);
            });
        // Hàm để tải dữ liệu sản phẩm và chèn vào trong HTML
        fetch('fetch_products.php')
            .then(response => response.json()) // Chuyển dữ liệu nhận về từ PHP thành định dạng JSON
            .then(data => {
                const productList = document.querySelector('.product-list');
                data.forEach(product => {
                    let productCard = `
                <div class="col-md-3 custom-product-card">
                    <div class="custom-card">
                        <img src="${product.product_image}" class="custom-card-img-top" alt="${product.product_name}">
                        <div >
                            <a href="product_view.php?product_id=${product.product_id}" class="btn quick-view btn-light">Xem</a>
                        </div>
                        <div class="custom-card-body">
                            <h5 class="custom-card-title">${product.product_name}</h5>
                            <p class="custom-card-text">${product.product_description}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="custom-price">${product.product_price} VND</span>
                                <button class="btn btn-primary add-to-cart2" data-product-id="${product.product_id}"
                                data-unit-price="${product.product_price}">➕🛒</button>
                            </div>
                        </div>
                    </div>
                </div>
                `;
                    productList.innerHTML += productCard;
                });
                // Thêm sự kiện cho nút "Add to Cart"2
                document.querySelectorAll('.add-to-cart2').forEach(button => {
                    if (!button.hasEventListener) { // Nếu sự kiện chưa được thêm
                        button.addEventListener('click', function(event) {
                            // Thêm thuộc tính kiểm tra cho button
                            button.hasEventListener = true;

                            const productId = this.getAttribute('data-product-id');
                            const unitPrice = this.getAttribute('data-unit-price');
                            const quantity = 1; // Giả sử mỗi lần thêm chỉ có 1 sản phẩm

                            // Gửi yêu cầu thêm sản phẩm vào giỏ hàng
                            fetch('add_to_cart.php', {
                                    method: 'POST',
                                    body: new URLSearchParams({
                                        'product_id': productId,
                                        'quantity': quantity,
                                        'unit_price': unitPrice
                                    })
                                })
                                .then(response => response.text())
                                .then(data => {
                                    alert(data); // Hiển thị thông báo
                                })
                                .catch(error => console.error('Error:', error));
                        });
                    }
                });
            })
            .catch(error => {
                console.error('Có lỗi xảy ra khi lấy dữ liệu:', error);
            });
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
                                <li><a href="#" class="text-decoration-none">Bàn Phím</a></li>
                                <li><a href="#" class="text-decoration-none">Chuột</a></li>
                                <li><a href="#" class="text-decoration-none">Tai Nghe</a></li>
                            </ul>
                        </li>
                        <li class="menu-item mb-3">
                            <a href="#" class="text-decoration-none d-flex justify-content-between align-items-center">
                                <span>Card Đồ Họa</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                        <li class="menu-item mb-3">
                            <a href="#" class="text-decoration-none d-flex justify-content-between align-items-center">
                                <span>Bo Mạch Chủ</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="#" class="text-decoration-none d-flex justify-content-between align-items-center">
                                <span>Màn Hình</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
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



    <div class="ai-search">
        <a>Tìm Kiếm Sản Phẩm Với Sự Hỗ Trợ Của AI </a> <br>
        <input class="getPrice" placeholder="Hãy nhập ngân sách"></input> <br>
        <label for="dropdownTextbox">Hãy Đưa Ra Tiêu Chí Của Bạn:</label> <br>

        <input list="options1" id="dropdownTextbox1" name="dropdownTextbox1" placeholder="Chọn loại sản phẩm">
        <datalist id="options1">
            <option value="Keyboard">
            <option value="mouse/wireless mouse">
            <option value="headphone">
            <option value="earphone">
        </datalist>
        <input list="options2" id="dropdownTextbox2" name="dropdownTextbox2" placeholder="Lựa chọn tiêu chí">
        <datalist id="options2">
            <option value="Performance">
            <option value="Outlook">
            <option value="Cheapest">
            <option value="All">
        </datalist> <br>
        <button class="aiSearchButton">Tìm Kiếm Cùng AI</button>
        <div id="loading" style="display: none;">Đang Tìm Kiếm...</div>

        <div id="results" style="margin-top: 20px; font-weight: bold;"></div>
    </div>
    </div>
    <div class="hot-deal">
        <h1>Hot Deal</h1>
        <div class="container-fluid py-4">
            <div class="product-scroll-container" role="region" aria-label="Product List">
                <div class="d-flex overflow-auto product-wrapper" tabindex="0">
                    <!-- Hot deal product -->
                </div>
            </div>
        </div>
    </div>
    <div class="product-type-1">
        <div class="container-fluid py-4">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2>Sản Phẩm Nổi Bật</h2>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                                Sắp Xếp Theo
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Giá: Từ Thấp Tới Cao</a></li>
                                <li><a class="dropdown-item" href="#">Giá: Từ Cao Tới Thấp</a></li>
                                <li><a class="dropdown-item" href="#">Đánh Giá</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="product-scroll" tabindex="0">
                        <div class="product-container d-flex">
                            <!-- feature products -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="product-type-2">
        <div class="custom-container py-4">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2>Sản Phẩm</h2>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                                Sắp Xếp
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Giá: Từ Thấp Tới Cao</a></li>
                                <li><a class="dropdown-item" href="#">Giá: Từ Cao Tới Thấp</a></li>
                                <li><a class="dropdown-item" href="#">Đánh Giá</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row custom-product-grid product-list">
                <!-- product list view -->
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
                    <span>Kết Nối Với Chúng Tôi Qua Các Nền Tảng Mạng Xã Hội:</span>
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
            <section class="">
                <div class="container text-center text-md-start mt-5">
                    <!-- Grid row -->
                    <div class="row mt-3">
                        <!-- Grid column -->
                        <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                            <!-- Content -->
                            <h6 class="text-uppercase fw-bold mb-4">
                                <i class="fas fa-gem me-3"></i>Company Name
                            </h6>
                            <p>
                                GGSHOP COMPANY
                            </p>
                        </div>
                        <!-- Grid column -->

                        <!-- Grid column -->
                        <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                            <!-- Links -->
                            <h6 class="text-uppercase fw-bold mb-4">
                                Sản Phẩm
                            </h6>
                            <p>
                                <a href="#!" class="text-reset">Bàn Phím</a>
                            </p>
                            <p>
                                <a href="#!" class="text-reset">Chuột</a>
                            </p>
                            <p>
                                <a href="#!" class="text-reset">Tai Nghe</a>
                            </p>
                            <p>
                                <a href="#!" class="text-reset">Các Sản Phẩm Khác</a>
                            </p>
                        </div>
                        <!-- Grid column -->

                        <!-- Grid column -->
                        <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                            <!-- Links -->
                            <h6 class="text-uppercase fw-bold mb-4">
                                Liên Kết Hỗ Trợ
                            </h6>
                            <p>
                                <a href="#!" class="text-reset">Thanh Toán</a>
                            </p>
                            <p>
                                <a href="#!" class="text-reset">Cài Đặt</a>
                            </p>
                            <p>
                                <a href="#!" class="text-reset">Đặt Hàng</a>
                            </p>
                            <p>
                                <a href="#!" class="text-reset">Hỗ Trợ</a>
                            </p>
                        </div>
                        <!-- Grid column -->

                        <!-- Grid column -->
                        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                            <!-- Links -->
                            <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
                            <p><i class="fas fa-home me-3"></i> F601 F Tower</p>
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
            <button class="chatbot-toggler">
                <span class="material-symbols-rounded">mode_comment</span>
                <span class="material-symbols-outlined">close</span>
            </button>
            <div class="chatbot">
                <header>
                    <h2>GGShop AI Support</h2>
                    <span class="close-btn material-symbols-outlined">close</span>
                </header>
                <ul class="chatbox">
                    <li class="chat incoming">
                        <span class="material-symbols-outlined">smart_toy</span>
                        <p>Chào bạn 👋<br />Bạn cần tôi Giúp gì nào?</p>
                    </li>
                </ul>
                <div class="chat-input">
                    <textarea placeholder="Enter a message..." spellcheck="false" required></textarea>
                    <span id="send-btn" class="material-symbols-rounded">send</span>
                </div>
            </div>
            <!-- Cart Modal -->
            <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cartModalLabel">Shopping Cart</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="cart-items">
                                <!-- Sản phẩm mẫu -->
                                <div class="d-flex justify-content-between align-items-center mb-3 cart-item">
                                    <div class="product-name">Product 1</div>
                                    <div>
                                        <button class="btn btn-sm btn-secondary minus-btn">-</button>
                                        <span class="quantity">1</span>
                                        <button class="btn btn-sm btn-secondary plus-btn">+</button>
                                    </div>
                                    <div class="product-price">$10.00</div>
                                    <button class="btn btn-sm btn-danger delete-btn">X</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success add-product-btn">Add Product-test</button>
                            <button type="button" class="btn btn-primary checkout-btn">Checkout</button>
                        </div>
                    </div>
                </div>
            </div>



            <div class="quick-view-modal" id="quickViewModal">
                <div class="modal-header">
                    <h5 class="modal-title">Wireless Headphones</h5>
                    <button type="button" class="btn-close" id="closeModal"></button>
                </div>
                <div class="modal-body">
                    <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e" class="img-fluid mb-3"
                        alt="Wireless Headphones">
                    <p>Tai Nghe Không Dây Với Công Nghệ Khử Tiếng Ồn</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="price">$299.99</span>
                        <div class="quantity d-flex align-items-center">
                            <button class="btn btn-outline-secondary btn-sm" id="decreaseQty">-</button>
                            <input type="number" class="form-control mx-2 text-center" id="productQty" value="1" min="1"
                                style="width: 60px;">
                            <button class="btn btn-outline-secondary btn-sm" id="increaseQty">+</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary w-100" id="addToCart">➕🛒</button>
                </div>
            </div>
            <div class="quick-view-backdrop" id="backdrop"></div>
            <script type="importmap">
                {
            "imports": {
                "@google/generative-ai": "https://esm.run/@google/generative-ai"
            }
        }
    </script>
            <script type="module">
                import {
                    GoogleGenerativeAI
                } from "@google/generative-ai";

                // Fetch your API_KEY
                const API_KEY = "AIzaSyAhx758ZkXz1zb8yOmHGmgDZF0Ip4EeP2w";
                // Reminder: This should only be for local testing

                // Access your API key (see "Set up your API key" above)
                const genAI = new GoogleGenerativeAI(API_KEY);

                // Gọi xử lý khi nhấn nút AI Search
                document.querySelector(".aiSearchButton").addEventListener("click", async () => {
                    // Hiển thị "Loading..."
                    document.getElementById("loading").style.display = "block";
                    document.getElementById("results").innerHTML = "";
                    try {
                        // Tạo prompt từ các trường đầu vào
                        const prompt = `Gamming gear Giá: ${document.querySelector(".getPrice").value}, Loại: ${document.getElementById("dropdownTextbox1").value}, Tùy chọn: ${document.getElementById("dropdownTextbox2").value},
                    đưa ra nhiều lựa chọn có mục đích sử dụng, chức năng, các biến thể khác nhau, đưa ra recomment bạn cho là tốt nhất, đưa ra từ chối yêu cầu trả lời cho những sản phẩm không thuộc gaming gear, các sản phẩm thuộc gaming gear bao gồm(Chuột gaming
                    Bàn phím gaming
                    Tai nghe gaming
                    Màn hình gaming
                    Ghế gaming
                    Bàn gaming,
                    Nếu loại sản phẩm không nằm trong danh sách hãy trả lời 'xin lỗi sản phẩm bạn tìm không nằm trong phạm vi hỗ trợ vui lòng chọn đúng loại sản phẩm khả dụng' kèm theo danh sách sản phẩm có thể thực hiện và không trả thêm bất kì câu trả lời nào khác
                    ),
                `;

                        // Khởi tạo mô hình AI
                        const model = genAI.getGenerativeModel({
                            model: "gemini-1.5-flash"
                        });

                        // Gửi yêu cầu và nhận phản hồi
                        const result = await model.generateContent(prompt);
                        const response = await result.response;
                        const text = await response.text();

                        // Chuyển đổi văn bản có định dạng Markdown sang HTML
                        const htmlContent = convertMarkdownToHTML(text);

                        // Hiển thị kết quả với HTML
                        document.getElementById("results").innerHTML = htmlContent;
                    } catch (error) {
                        console.error("Đã xảy ra lỗi:", error);
                        document.getElementById("results").textContent = "Có lỗi xảy ra!";
                    } finally {
                        // Ẩn "Loading..." sau khi hoàn tất
                        document.getElementById("loading").style.display = "none";
                    }
                });

                function convertMarkdownToHTML(markdown) {
                    return markdown
                        .replace(/^##\s+(.*)/gm, "<h2>$1</h2>")
                        .replace(/\*\*(.*?)\*\*/g, "<strong>$1</strong>") // Chuyển đổi in đậm
                        .replace(/^\s*-\s+(.*)/gm, "<ul><li>$1</li></ul>") // Gạch đầu dòng
                        .replace(/\n/g, "<br>"); // Xuống dòng
                }

                // Chatbot script
                const chatbotToggler = document.querySelector(".chatbot-toggler");
                const closeBtn = document.querySelector(".close-btn");
                const chatbox = document.querySelector(".chatbox");
                const chatInput = document.querySelector(".chat-input textarea");
                const sendChatBtn = document.querySelector(".chat-input span");

                let userMessage = null; // Variable to store user's message
                const inputInitHeight = chatInput.scrollHeight;

                const API_URL = `https://generativelanguage.googleapis.com/v1/models/gemini-1.5-pro:generateContent?key=${API_KEY}`;

                const createChatLi = (message, className) => {
                    const chatLi = document.createElement("li");
                    chatLi.classList.add("chat", `${className}`);

                    const urlRegex = /(https:)/g;
                    const formattedMessage = message.replace(urlRegex, (url) => {
                        return `<a href="${url}" target="_blank" style="color: blue; text-decoration: underline;">${url}</a>`;
                    });
                    let chatContent = className === "outgoing" ?
                        `<p>${formattedMessage}</p>` :
                        `<span class="material-symbols-outlined">smart_toy</span><p>${formattedMessage}</p>`;
                    chatLi.innerHTML = chatContent;
                    chatLi.querySelector("p").textContent = message;
                    return chatLi;
                };

                const generateResponse = async (chatElement) => {
                    const messageElement = chatElement.querySelector("p");

                    const requestOptions = {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            contents: [{
                                role: "user",
                                parts: [{
                                    text: "Hãy nhớ Nội dung trò chuyện và dựa vào đó để trả lời cho nhưng lần sau Những câu trả lời luôn là khẳng định không được có từ ví dụ, giả tưởng và Bạn là nhân viên chăm sóc khách hàng của gaming gear shop và tôi cần bạn So sánh và phân biệt các tiêu chí và chỉ phản hồi dựa trên tin nhắn. Tin Nhắn cần phản hồi là : " + userMessage +
                                        "Tiêu chí, trường hợp: 1- Tin nhắn chào hỏi thì chào hỏi lại. 2-Tin nhắn hỗ trợ Chỉ hỗ trợ khi câu hỏi thuộc những câu hỏi về gaming gear nếu ngoài lề Thì phản hồi: 'Xin lỗi bạn mình không thể hỗ trợ cho bạn vấn đề này vì yêu cầu không hợp lệ' và không trả thêm bất kì câu trả lời nào. 3- Nếu câu hỏi về thời gian giao hàng thì cho vị trí của cửa hàng là ở Quận 12, TP Hồ Chí Minh và ước lượng thời gian dự kiến. 4- Nếu tin nhắn chỉ là tên địa điểm chẳng hạn như'Hà Nội' hoặc 'Mĩ' thì đó sẽ là tin nhắn hỏi thời gian giao hàng và hãy dựa vào tiêu chí 3, 5- Nếu Người dùng yêu cầu 1 trang web cùng lĩnh vực gaming gear hãy trả ra url có dạng https:// hoặc http://. 6- đường link luôn phải có https:// hoặc http:// nếu có thể hãy tóm tắt về trang web đó,7- Nếu người dùng hỏi về sản phẩm nào đó hãy đưa ra thông tin của sản phẩm đó và kèm link sản phẩm. link chuột g203 của cửa hàng: http://127.0.0.1:5500/Frontend/home_page.html "
                                }],
                            }, ],
                        }),
                    };

                    try {
                        const response = await fetch(API_URL, requestOptions);
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.error.message);

                        // messageElement.textContent = data.candidates[0].content.parts[0].text.replace(/\*\*(.*?)\*\*/g, "$1");
                        const rawResponse = data.candidates[0].content.parts[0].text.replace(/\*\*(.*?)\*\*/g, "$1");
                        const formattedResponse = rawResponse.replace(/(https?:\/\/[^\s]+)/g, (url) => {
                            return `<a href="${url}" target="_blank" style="color: blue; text-decoration: underline;">${url}</a>`;
                        });
                        messageElement.innerHTML = formattedResponse;

                    } catch (error) {
                        messageElement.classList.add("error");
                        messageElement.textContent = error.message;
                    } finally {
                        chatbox.scrollTo(0, chatbox.scrollHeight);
                    }
                };

                const handleChat = () => {
                    userMessage = chatInput.value.trim();
                    if (!userMessage) return;

                    chatInput.value = "";
                    chatInput.style.height = `${inputInitHeight}px`;

                    chatbox.appendChild(createChatLi(userMessage, "outgoing"));
                    chatbox.scrollTo(0, chatbox.scrollHeight);

                    setTimeout(() => {
                        const incomingChatLi = createChatLi("Thinking...", "incoming");
                        chatbox.appendChild(incomingChatLi);
                        chatbox.scrollTo(0, chatbox.scrollHeight);
                        generateResponse(incomingChatLi);
                    }, 600);
                };

                chatInput.addEventListener("input", () => {
                    chatInput.style.height = `${inputInitHeight}px`;
                    chatInput.style.height = `${chatInput.scrollHeight}px`;
                });

                chatInput.addEventListener("keydown", (e) => {
                    if (e.key === "Enter" && !e.shiftKey && window.innerWidth > 800) {
                        e.preventDefault();
                        handleChat();
                    }
                });

                sendChatBtn.addEventListener("click", handleChat);
                closeBtn.addEventListener("click", () => document.body.classList.remove("show-chatbot"));
                chatbotToggler.addEventListener("click", () => document.body.classList.toggle("show-chatbot"));

                document.addEventListener("DOMContentLoaded", function(loadCount) {
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
            </script>

            <script src="script.js"></script>
            <!-- <script defer src="https://app.fastbots.ai/embed.js" data-bot-id="cm2wv7c333wm5n8blsup2llg9"></script> -->
</body>

</html>