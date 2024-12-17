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

// Khởi tạo biến để chứa thông báo
$message = '';
$message_type = '';
$redirect = false;

// Kiểm tra khi form đăng ký được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $gender = $_POST['gender'];

    // Kiểm tra xem mật khẩu có khớp không
    if ($password !== $confirm_password) {
        $message = "Mật khẩu và xác nhận mật khẩu không khớp!";
        $message_type = "alert-danger";
    } else {
        // Kiểm tra xem email đã tồn tại chưa
        $checkQuery = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($checkQuery);

        if ($result->num_rows > 0) {
            $message = "Email đã tồn tại!";
            $message_type = "alert-danger";
        } else {
            // Mã hóa mật khẩu trước khi lưu vào cơ sở dữ liệu
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Thêm người dùng vào cơ sở dữ liệu
            $insertQuery = "INSERT INTO users (user_fullname, email, user_address, phone_number, password, user_gender, role) 
                            VALUES ('$full_name', '$email', '$address', '$phone', '$hashedPassword', '$gender', 'CUSTOMER')";

            // Thực hiện câu lệnh SQL
            if ($conn->query($insertQuery) === TRUE) {
                $message = "Đăng ký thành công! Bạn sẽ được chuyển hướng về trang chủ sau 5 giây.";
                $message_type = "alert-success";
                $redirect = true; 
            } else {
                $message = "Lỗi: " . $conn->error;
                $message_type = "alert-danger";
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
  <link rel="stylesheet" href="../Frontend/style.css">
  <style>
    /* Register style css */
    body {
        background: linear-gradient(to right, #2575fc, #6a11cb);
        height: 1000px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .registration-container {
    background: #fff;
    border-radius: 10px;
    /* display: flex; */
    justify-content: center;
    /* align-items: center; */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: 600px;
    height: 800px;
    margin: auto;
    }

    .registration-form h2 {
    margin-bottom: 20px;
    display: flex;
    justify-content: center;
    margin: auto;
    color: #333;
    padding: 5%;
    }

    .form-group {
    margin-bottom: 15px;
    padding: 0px 10%;
    
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

    .radio-group {
    display: flex;
    justify-content: space-between;
    
    align-items: center;
    text-align: center;
    }

    .radio-group label {
    display: flex;
    flex-direction: row;
    align-items: center;
    font-size: 14px;
    color: #555;
    }
    

    .submit-button {
    display: flex;
    width: 90%;
    background: linear-gradient(to right, #2575fc, #6a11cb);
    color: #fff;
    font-size: 16px;
    padding: 10px ;
    justify-content:center;
    margin: auto;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s ease;
    }

    .submit-button:hover {
    background: linear-gradient(to right, #ff512f, #f09819);
    }

    .alert {
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 4px;
        text-align: center;
    }

    .alert-success {
        background-color: #4CAF50;
        color: white;
    }

    .alert-danger {
        background-color: #f44336;
        color: white;
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
  <div class="overlogin">
    <div class="registration-container">
      <!-- Hiển thị thông báo -->
      <?php
            if (isset($message)) {
                echo "<div class='alert $message_type'>$message</div>";
            }

            if ($redirect) {
                echo "<div class='countdown'>Chuyển hướng trong <span id='countdown'>5</span> giây...</div>";
            }
        ?>

      <form class="registration-form" method="POST" action="">
        <h2>Đăng Ký Tài Khoản GGShop</h2>

        <div class="form-group">
          <label for="full-name">Tên Đầy Đủ</label>
          <input type="text" id="full-name" name="full_name" placeholder="Nhập Tên Của Bạn" required>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Nhập Email Của Bạn" required>
        </div>

        <div class="form-group">
          <label for="address">Địa Chỉ</label>
          <input type="text" id="address" name="address" placeholder="Hãy Cho Chúng Tôi Biết Địa Chỉ Của Bạn" required>
        </div>

        <div class="form-group">
          <label for="phone">Số Liên Lạc</label>
          <input type="text" id="phone" name="phone" placeholder="Hãy Nhập Số Điện Thoại Của Bạn" required>
        </div>

        <div class="form-group">
          <label for="password">Mật Khẩu</label>
          <input type="password" id="password" name="password" placeholder="Hãy Nhập Mật Khẩu" required>
        </div>

        <div class="form-group">
          <label for="confirm-password">Xác Minh Mật Khẩu</label>
          <input type="password" id="confirm-password" name="confirm_password" placeholder="Nhập Lại Mật Khẩu" required>
        </div>

        <div class="form-group">
          <label>Giới Tính</label>
          <div class="radio-group">
            <label><input type="radio" name="gender" value="male"> Nam</label>
            <label><input type="radio" name="gender" value="female">  Nữ </label>
            <label><input type="radio" name="gender" value="other"> Khác </label>
          </div>
        </div>

        <button type="submit" class="submit-button">Đăng Ký</button>
      </form>
    </div>
  </div>  
  <?php if ($redirect): ?>
        <script>
            let countdownElement = document.getElementById('countdown');
            let countdown = 5;

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
