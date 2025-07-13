<?php
include '../connect.php';

if (isset($_GET['id'])) {
    $orderId = intval($_GET['id']);

    // Update order status to 'confirmed'
    $sql = "UPDATE orders SET status = 'confirmed' WHERE id = $orderId";

    if (mysqli_query($conn, $sql)) {
        header("Location: orders.php?confirmed=1");
        exit;
    } else {
        header("Location: orders.php?error=1");
        exit;
    }
} else {
    header("Location: orders.php");
    exit;
}
?>
