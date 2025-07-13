<?php
session_start();
include 'connect.php';

// Validate input
if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id']) || !isset($_POST['qty'])) {
    header("Location: menu.php?error=invalid_input");
    exit;
}

$product_id = (int) $_POST['product_id'];
$qty = (int) $_POST['qty'];

// Limit quantity to avoid abuse
if ($qty < 1 || $qty > 10) {
    header("Location: product.php?id=$product_id&error=invalid_quantity");
    exit;
}

// Use prepared statement to avoid SQL injection
$stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: menu.php?error=product_not_found");
    exit;
}

$product = $result->fetch_assoc();
$stmt->close();

// Initialize cart if not already
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// If product already in cart, increase quantity
if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['qty'] += $qty;
} else {
    $_SESSION['cart'][$product_id] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'image' => $product['image'],
        'qty' => $qty
    ];
}

// Redirect with success message
header("Location: cart.php?added=success");
exit;
?>
