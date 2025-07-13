<?php
session_start();
include '../connect.php'; // adjust path if needed

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Update status to 0 (pending/disapproved)
    $sql = "UPDATE testimonials SET status = 0 WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Testimonial disapproved successfully.";
    } else {
        $_SESSION['message'] = "Error disapproving testimonial: " . mysqli_error($conn);
    }
} else {
    $_SESSION['message'] = "Invalid testimonial ID.";
}

header("Location: testimonials.php");
exit;
?>
