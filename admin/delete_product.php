<?php
session_start();
include '../connect.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch the image filename to delete from folder
$getQuery = "SELECT image FROM products WHERE id = $id";
$getResult = mysqli_query($conn, $getQuery);

if ($getResult && mysqli_num_rows($getResult) > 0) {
    $row = mysqli_fetch_assoc($getResult);
    $imagePath = '../uploads/' . $row['image'];

    // Delete product from database
    $deleteQuery = "DELETE FROM products WHERE id = $id";
    if (mysqli_query($conn, $deleteQuery)) {
        // Delete image from folder if exists
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        header("Location: products.php?deleted=1");
        exit();
    } else {
        header("Location: products.php?error=delete_failed");
        exit();
    }
} else {
    header("Location: products.php?error=not_found");
    exit();
}
?>
