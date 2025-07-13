<?php
// Start session and include DB connection
session_start();
include '../connect.php'; // adjust if needed

// Check if ID is passed
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Update status to 1 (approved)
    $sql = "UPDATE testimonials SET status = 1 WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Testimonial approved successfully.";
    } else {
        $_SESSION['message'] = "Error approving testimonial: " . mysqli_error($conn);
    }
} else {
    $_SESSION['message'] = "Invalid testimonial ID.";
}

// ✅ Fix the redirect path
header("Location: testimonials.php");
exit;
?>
