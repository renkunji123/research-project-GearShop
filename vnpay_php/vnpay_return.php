<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>VNPAY RESPONSE</title>
        <!-- Bootstrap core CSS -->
        <link href="../vnpay_php/assets/bootstrap.min.css" rel="stylesheet"/>
        <!-- Custom styles for this template -->
        <link href="../vnpay_php/assets/jumbotron-narrow.css" rel="stylesheet">  
        <style>
            body {
                background-color: #f8f9fa;
                font-family: Arial, sans-serif;
                padding-top: 50px;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            .result-label {
                font-size: 18px;
                font-weight: bold;
                text-align: center;
                margin-top: 20px;
            }
            .footer {
                text-align: center;
                margin-top: 30px;
                font-size: 14px;
            }
            .countdown {
                font-size: 20px;
                color: #333;
                text-align: center;
                margin-top: 20px;
            }
            .alert {
                padding: 15px;
                margin-bottom: 20px;
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
        </style>
        <script src="../vnpay_php/assets/jquery-1.11.3.min.js"></script>
        <script>
            // Đếm ngược 5 giây
            var countdown = 5;
            var countdownTimer = setInterval(function() {
                if (countdown <= 0) {
                    clearInterval(countdownTimer);
                    window.location.href = '../homepage.php'; // Chuyển hướng về trang chính sau 5 giây
                } else {
                    document.getElementById('countdown').innerHTML = countdown + " giây còn lại";
                    countdown--;
                }
            }, 1000);
        </script>
    </head>
    <body>
        <?php
        require_once("./config.php");
        $vnp_SecureHash = $_GET['vnp_SecureHash'];
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        ?>
        <!--Begin display -->
        <div class="container">
            <div class="result-label">
                <label>Kết quả thanh toán:</label>
            </div>
            <div class="alert <?php echo ($secureHash == $vnp_SecureHash && $_GET['vnp_ResponseCode'] == '00') ? 'alert-success' : 'alert-danger'; ?>">
                <?php
                if ($secureHash == $vnp_SecureHash) {
                    if ($_GET['vnp_ResponseCode'] == '00') {
                        echo "Giao dịch thành công!";
                    } else {
                        echo "Giao dịch không thành công. Mã lỗi: " . $_GET['vnp_ResponseCode'];
                    }
                } else {
                    echo "Chu ký không hợp lệ. Vui lòng thử lại.";
                }
                ?>
            </div>
            <div class="countdown" id="countdown">
                Đang đợi chuyển hướng về trang chính...
            </div>
            <footer class="footer">
                <p>&copy; VNPAY <?php echo date('Y'); ?></p>
            </footer>
        </div>  
    </body>
</html>
