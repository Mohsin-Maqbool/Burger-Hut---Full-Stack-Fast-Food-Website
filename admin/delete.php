<?php
session_start();
include '../connect.php'; // Adjust path if needed

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch the testimonial to delete photo if exists
    $query = mysqli_query($conn, "SELECT photo FROM testimonials WHERE id = $id");
    $row = mysqli_fetch_assoc($query);

    // Delete photo file from server (if it exists)
    if ($row && !empty($row['photo']) && file_exists("../" . $row['photo'])) {
        unlink("../" . $row['photo']);
    }

    // Delete testimonial from database
    $sql = "DELETE FROM testimonials WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Testimonial deleted successfully.";
    } else {
        $_SESSION['message'] = "Error deleting testimonial: " . mysqli_error($conn);
    }
} else {
    $_SESSION['message'] = "Invalid testimonial ID.";
}

header("Location: testimonials.php");
exit;
?>
