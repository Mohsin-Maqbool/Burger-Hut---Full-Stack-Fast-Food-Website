<?php
include '../connect.php';
$id = $_GET['id'];
$action = $_GET['action'];

if ($action == 'approve') {
  mysqli_query($conn, "UPDATE testimonials SET status='approved' WHERE id=$id");
} elseif ($action == 'disapprove') {
  mysqli_query($conn, "UPDATE testimonials SET status='pending' WHERE id=$id");
} elseif ($action == 'delete') {
  mysqli_query($conn, "DELETE FROM testimonials WHERE id=$id");
}
header("Location: testimonials.php");
?>
