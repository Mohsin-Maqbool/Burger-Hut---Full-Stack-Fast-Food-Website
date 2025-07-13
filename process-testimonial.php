<?php
session_start();
include 'connect.php'; // adjust path if needed

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $rating = $_POST['rating'];
    $message = $_POST['message'];
    $photo_path = '';

    // Create uploads directory if not exists
    $upload_dir = 'uploads/testimonials/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Handle optional photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo_name = time() . '_' . basename($_FILES['photo']['name']);
        $target = $upload_dir . $photo_name;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            $photo_path = $target;
        }
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO testimonials (name, rating, message, photo, status, created_at) VALUES (?, ?, ?, ?, 0, NOW())");
    $stmt->bind_param("siss", $name, $rating, $message, $photo_path);

    if ($stmt->execute()) {
        $_SESSION['message'] = "✅ Testimonial submitted! Awaiting admin approval.";
    } else {
        $_SESSION['message'] = "❌ Failed to submit testimonial. Please try again.";
    }

    header("Location: index.php?testimonial_submitted=1");
    exit();
}
