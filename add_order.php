<?php
session_start();
include 'db.php'; // Kết nối tới database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user']['id'];
    $total_amount = $_POST['amount'];
    $order_date = date('Y-m-d H:i:s'); // Lấy thời gian hiện tại
    $payment_date = $order_date;
    $order_status = 'COMPLETED';
    $payment_status = 'PAID';
    $payment_method_id = 1;
    $shipment_address = $_SESSION['user']['address'];
    $shipment_status = 'IN_TRANSIT';

    $sql = "INSERT INTO orders (
                user_id, 
                order_date, 
                total_amount, 
                order_status, 
                payment_method_id, 
                payment_date, 
                payment_status, 
                shipment_address, 
                shipment_status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "isdisssss", 
        $user_id, 
        $order_date, 
        $total_amount, 
        $order_status, 
        $payment_method_id, 
        $payment_date, 
        $payment_status, 
        $shipment_address, 
        $shipment_status
    );

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Order created successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create order.']);
    }

    $stmt->close();
    $conn->close();
}
?>
