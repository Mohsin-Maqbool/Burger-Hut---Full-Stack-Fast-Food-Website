<?php
include '../connect.php';

if (isset($_GET['id'])) {
    $orderId = intval($_GET['id']);

    // Delete the order
    $sql = "DELETE FROM orders WHERE id = $orderId";

    if (mysqli_query($conn, $sql)) {
        header("Location: orders.php?deleted=1");
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
