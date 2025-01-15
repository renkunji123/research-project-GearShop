<?php
session_start();

// Database connection function
function getDbConnection()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ggshopdb";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        error_log("Database Connection Error: " . $conn->connect_error);
        die("Connection failed. Please try again later.");
    }

    return $conn;
}

// Add a robust session check function


// Fetch user profile data
function getUserProfile($userId)
{
    $conn = getDbConnection();

    $stmt = $conn->prepare("SELECT user_fullname, email, phone_number, user_address, user_gender FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $profile = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

    return $profile;
}

// Update user profile
function updateUserProfile($userId, $fullName, $phoneNumber, $address, $gender)
{
    $conn = getDbConnection();

    $stmt = $conn->prepare("UPDATE users SET user_fullname = ?, phone_number = ?, user_address = ?, user_gender = ? WHERE user_id = ?");
    $stmt->bind_param("ssssi", $fullName, $phoneNumber, $address, $gender, $userId);

    $result = $stmt->execute();

    $stmt->close();
    $conn->close();

    return $result;
}

// Change user password
function changeUserPassword($userId, $oldPassword, $newPassword)
{
    $conn = getDbConnection();

    // First, verify the old password
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Password complexity validation
    if (strlen($newPassword) < 8) {
        return false;
    }

    // Verify password (using password_verify for secure password checking)
    if (!password_verify($oldPassword, $user['password'])) {
        $stmt->close();
        $conn->close();
        return false;
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update password
    $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
    $updateStmt->bind_param("si", $hashedPassword, $userId);
    $updateResult = $updateStmt->execute();

    $updateStmt->close();
    $conn->close();

    return $updateResult;
}

// Handle profile update and password change via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    

    // Add an extra check for user_id
    if (!isset($_SESSION['user']['id'])) {
        $response = [
            'success' => false, 
            'message' => 'Phiên đăng nhập không hợp lệ. Vui lòng đăng nhập lại.'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    $userId = $_SESSION['user']['id'];
    $response = ['success' => false, 'message' => ''];

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_profile':
                $fullName = $_POST['fullName'] ?? '';
                $phoneNumber = $_POST['phoneNumber'] ?? '';
                $address = $_POST['address'] ?? '';
                $gender = $_POST['gender'] ?? '';

                // Input validation
                if (empty($fullName)) {
                    $response['message'] = 'Vui lòng nhập tên';
                    break;
                }

                if (updateUserProfile($userId, $fullName, $phoneNumber, $address, $gender)) {
                    $response['success'] = true;
                    $response['message'] = 'Cập nhật hồ sơ thành công';

                    // Update session data
                    $_SESSION['user']['name'] = $fullName;
                } else {
                    $response['message'] = 'Cập nhật hồ sơ thất bại';
                }
                break;

            case 'change_password':
                $oldPassword = $_POST['oldPassword'] ?? '';
                $newPassword = $_POST['newPassword'] ?? '';
                $confirmPassword = $_POST['confirmPassword'] ?? '';

                // Validation checks
                if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
                    $response['message'] = 'Vui lòng điền đầy đủ thông tin';
                    break;
                }

                if ($newPassword !== $confirmPassword) {
                    $response['message'] = 'Mật khẩu mới và xác nhận không trùng khớp';
                    break;
                }

                if (strlen($newPassword) < 8) {
                    $response['message'] = 'Mật khẩu phải có ít nhất 8 ký tự';
                    break;
                }

                if (changeUserPassword($userId, $oldPassword, $newPassword)) {
                    $response['success'] = true;
                    $response['message'] = 'Đổi mật khẩu thành công';
                } else {
                    $response['message'] = 'Mật khẩu cũ không chính xác hoặc không đáp ứng yêu cầu';
                }
                break;
        }
    }

    // Send JSON response for AJAX
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}



// Add error handling when fetching profile
try {
    $profile = getUserProfile($_SESSION['user']['id']);

    // Additional check in case getUserProfile returns null
    if ($profile === null) {
        throw new Exception("Không thể tải thông tin người dùng.");
    }
} catch (Exception $e) {
    // Log the error
    error_log($e->getMessage());

    // Redirect with an error message
    $_SESSION['error_message'] = $e->getMessage();
    
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Hồ Sơ Người Dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .error-message { color: red; }
        .success-message { color: green; }
    </style>
</head>

<body>
    <header class="d-flex align-items-center justify-content-between py-3 px-4 border-bottom">
        <ul class="nav me-auto">
            <li><a href="../homepage.php" class="nav-link px-2 link-secondary">Trang Chủ</a></li>
        </ul>

        
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="userDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                    <?= htmlspecialchars($_SESSION['user']['name']) ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="profile.php">
                            <i class="bi bi-person-fill"></i> Hồ Sơ
                        </a></li>
                    <?php if ($_SESSION['user']['role'] === 'ADMIN'): ?>
                        <li><a class="dropdown-item" href="../adminPage.php">
                                <i class="bi bi-shield-lock"></i> Quản Trị Viên
                            </a></li>
                    <?php endif; ?>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../logout.php">
                            <i class="bi bi-door-open"></i> Đăng Xuất
                        </a></li>
                </ul>
            </div>
       
    </header>

    <div class="container mt-4">
        <div class="profile-container">
            <div class="profile-header mb-4">
                <h2 class="profile-name"><?= htmlspecialchars($profile['user_fullname']) ?></h2>
            </div>

            <div class="profile-details">
                <div class="detail-item mb-3">
                    <h4>Email</h4>
                    <p class="form-control-plaintext"><?= htmlspecialchars($profile['email']) ?></p>
                </div>
                <div class="detail-item mb-3">
                    <h4>Số điện thoại</h4>
                    <input type="text" id="phoneNumber" class="form-control" 
                           value="<?= htmlspecialchars($profile['phone_number'] ?? '') ?>"
                           placeholder="Nhập số điện thoại">
                </div>
                <div class="detail-item mb-3">
                    <h4>Địa chỉ</h4>
                    <input type="text" id="address" class="form-control" 
                           value="<?= htmlspecialchars($profile['user_address'] ?? '') ?>"
                           placeholder="Nhập địa chỉ">
                </div>
                <div class="detail-item mb-3">
                    <h4>Giới tính</h4>
                    <select id="gender" class="form-control">
                        <option value="MALE" <?= $profile['user_gender'] === 'MALE' ? 'selected' : '' ?>>Nam</option>
                        <option value="FEMALE" <?= $profile['user_gender'] === 'FEMALE' ? 'selected' : '' ?>>Nữ</option>
                        <option value="OTHER" <?= $profile['user_gender'] === 'OTHER' ? 'selected' : '' ?>>Khác</option>
                    </select>
                </div>
            </div>

            <div class="password-change mt-4">
                <h4>Đổi mật khẩu</h4>
                <div class="mb-3">
                    <input type="password" id="oldPassword" class="form-control" placeholder="Mật khẩu cũ">
                </div>
                <div class="mb-3">
                    <input type="password" id="newPassword" class="form-control" placeholder="Mật khẩu mới">
                </div>
                <div class="mb-3">
                    <input type="password" id="confirmPassword" class="form-control" placeholder="Nhập lại mật khẩu mới">
                </div>
                <p id="error-message" class="error-message" style="display: none;"></p>
                <p id="success-message" class="success-message" style="display: none;"></p>
            </div>

            <div class="profile-actions mt-4">
                <button class="btn btn-primary me-2" onclick="saveChanges()">Lưu thay đổi</button>
                <button class="btn btn-secondary" onclick="window.location.href='../logout.php'">Đăng xuất</button>
            </div>
        </div>
    </div>

    <script>
    function saveChanges() {
        // Reset message displays
        $('#error-message, #success-message').hide();

        // Prepare profile data
        var profileData = {
            action: 'update_profile',
            fullName: '<?= htmlspecialchars($profile['user_fullname']) ?>',
            phoneNumber: $('#phoneNumber').val(),
            address: $('#address').val(),
            gender: $('#gender').val()
        };

        // Check if password change is attempted
        var oldPassword = $('#oldPassword').val();
        var newPassword = $('#newPassword').val();
        var confirmPassword = $('#confirmPassword').val();

        var passwordChangeAttempted = oldPassword || newPassword || confirmPassword;

        if (passwordChangeAttempted) {
            // Validate password change
            if (newPassword !== confirmPassword) {
                $('#error-message').text('Mật khẩu mới và xác nhận không trùng khớp!').show();
                return;
            }

            // Prepare password change data
            var passwordData = {
                action: 'change_password',
                oldPassword: oldPassword,
                newPassword: newPassword,
                confirmPassword: confirmPassword
            };
        }

        // Combine profile and password data if password change is attempted
        var data = passwordChangeAttempted ? { ...profileData, ...passwordData } : profileData;

        $.ajax({
            url: '',
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#success-message').text(response.message).show();
                    $('#error-message').hide();

                    // Clear password fields
                    $('#oldPassword, #newPassword, #confirmPassword').val('');
                } else {
                    $('#error-message').text(response.message).show();
                    $('#success-message').hide();
                }
            },
            error: function () {
                $('#error-message').text('Đã xảy ra lỗi. Vui lòng thử lại.').show();
            }
        });
    }
    </script>
</body>
</html>