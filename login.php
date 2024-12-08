<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ggshopdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Khởi tạo thông báo
$message = "";
$message_type = "";
$redirect = false;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Kiểm tra email có tồn tại không
    $checkQuery = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Xác thực mật khẩu
        if (password_verify($password, $user['password'])) {
            $message = "Đăng nhập thành công! Bạn sẽ được chuyển hướng về trang chủ sau 3 giây.";
            $message_type = "alert-success";
            $redirect = true; 

            // Chuyển hướng hoặc lưu thông tin người dùng vào session
            session_start();
            $_SESSION['user'] = [
                'id' => $user['user_id'],
                'name' => $user['user_fullname'],
                'role' => $user['role']
            ];
            header("refresh:5; url=../research-project-GearShop/Frontend/home_page.html");
        } else {
            $message = "Sai mật khẩu!";
            $message_type = "alert-danger";

        }
    } else {
        $message = "Email không tồn tại!";
        $message_type = "alert-danger";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <style>
    /* CSS trực tiếp */
    body {
        background: linear-gradient(to right, #2575fc, #6a11cb);
        height: 1000px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .login-container {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px 30px;
    width:600px;
    }

    .login-form h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
    }

    .form-group {
    margin-bottom: 15px;
    }

    .form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #555;
    }

    .form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    }

    .button-group {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    }

    .login-button,
    .register-button {
    width: 48%;
    padding: 10px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    color: #fff;
    transition: background 0.3s ease;
    }

    .login-button {
    background: linear-gradient(to right, #6a11cb, #2575fc);
    }

    .register-button {
    background: linear-gradient(to right, #ff512f, #f09819);
    }

    .login-button:hover {
    background: linear-gradient(to right, #2575fc, #6a11cb);
    }

    .register-button:hover {
    background: linear-gradient(to right, #f09819, #ff512f);
    }

    .form-footer {
    text-align: center;
    margin-top: 10px;
    }

    .form-footer p {
    font-size: 14px;
    color: #666;
    }

    .form-footer a {
    color: #6a11cb;
    text-decoration: none;
    }

    .form-footer a:hover {
    text-decoration: underline;
    }

    .overlogin {
    width: 100%;
    background: linear-gradient(to right, #2575fc, #6a11cb);
    height: 1000px;
    display: flex;
    justify-content: center;
    align-items: center;

    }
    .alert {
        margin-bottom: 15px;
        padding: 10px;
        border-radius: 5px;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }
    .countdown {
        text-align: center;
        margin-top: 20px;
        font-size: 18px;
        color: #333;
    }
  </style>
</head>

<body>
  <div class="login-container">
    <form class="login-form" method="POST" action="">
      <h2>Login to GGShop</h2>
      <?php
            if (isset($message)) {
                echo "<div class='alert $message_type'>$message</div>";
            }

            if ($redirect) {
                echo "<div class='countdown'>Chuyển hướng trong <span id='countdown'>3</span> giây...</div>";
            }
        ?>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
      </div>

      <div class="button-group">
        <button type="submit" class="login-button">Login</button>
        <button type="button" class="register-button" onclick="window.location.href='register.php';">Register</button>
      </div>

      <div class="form-footer">
        <p>Doesn't have an account? <a href="register.php">Click here</a></p>
      </div>
    </form>
  </div>
  <?php if ($redirect): ?>
        <script>
            let countdownElement = document.getElementById('countdown');
            let countdown = 3;

            const interval = setInterval(() => {
                countdown--;
                countdownElement.textContent = countdown;

                if (countdown === 0) {
                    clearInterval(interval);
                    window.location.href = '../research-project-GearShop/Frontend/home_page.html'; // Đổi thành đường dẫn về trang chủ của bạn
                }
            }, 1000);
        </script>
    <?php endif; ?>
</body>
</html>
