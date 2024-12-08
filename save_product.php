<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ggshopdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get POST data
    $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    $stock_quantity = $_POST['stock_quantity'];
    $category_id = $_POST['category_id'];
    $brand_id = $_POST['brand_id'];
    
    // Handle image upload
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $product_image = 'images/' . basename($_FILES['product_image']['name']);
        move_uploaded_file($_FILES['product_image']['tmp_name'], $product_image);
    } else {
        $product_image = null; // No image uploaded, you can set a default image if needed
    }

    // Insert or update the product
    if ($product_id) {
        // Update existing product
        $sql = "UPDATE products SET product_name = ?, product_description = ?, product_price = ?, stock_quantity = ?, product_image = ?, category_id = ?, brand_id = ? WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdisiii", $product_name, $product_description, $product_price, $stock_quantity, $product_image, $category_id, $brand_id, $product_id);
    } else {
        // Insert new product
        $sql = "INSERT INTO products (product_name, product_description, product_price, stock_quantity, product_image, category_id, brand_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdisii", $product_name, $product_description, $product_price, $stock_quantity, $product_image, $category_id, $brand_id);
    }

    // Execute the query
    if ($stmt->execute()) {
        echo "Product saved successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
