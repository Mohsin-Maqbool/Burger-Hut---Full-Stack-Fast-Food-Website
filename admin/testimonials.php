<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../connect.php';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Testimonials</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .testimonial-card {
            border-left: 5px solid #ff5722;
            background-color: #fff;
        }

        .testimonial-card img {
            border: 2px solid #ddd;
        }
    </style>
</head>

<body>
<div class="container py-5">
    <h2 class="mb-4 display-6 text-primary fw-bold">📢 User Testimonials</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info">
            <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <?php
    $result = mysqli_query($conn, "SELECT * FROM testimonials ORDER BY created_at DESC");

    if (mysqli_num_rows($result) > 0):
        while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="card p-3 mb-3 testimonial-card">
                <strong><?= htmlspecialchars($row['name']) ?></strong> (<?= $row['rating'] ?> ⭐)<br>
                <p><?= nl2br(htmlspecialchars($row['message'])) ?></p>

                <?php if (!empty($row['photo'])): ?>
                    <img src="../<?= $row['photo'] ?>" width="60" class="rounded-circle mb-2" />
                <?php endif; ?>

                <div class="mt-2">
                    <?php if ($row['status'] == 0): ?>
                        <a href="approve.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Approve</a>
                    <?php else: ?>
                        <a href="disapprove.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Disapprove</a>
                    <?php endif; ?>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm ms-2" onclick="return confirm('Delete this testimonial?')">Delete</a>
                </div>
            </div>
    <?php endwhile;
    else:
        echo "<div class='alert alert-warning'>No testimonials found.</div>";
    endif;
    ?>
</div>

<!-- GSAP Animations -->
<script>
    gsap.from("h2", {
        duration: 1,
        y: -40,
        opacity: 0,
        ease: "power4.out"
    });

    gsap.from(".testimonial-card", {
        duration: 1,
        opacity: 0,
        y: 30,
        stagger: 0.2,
        ease: "power2.out"
    });

    gsap.from(".alert", {
        duration: 0.8,
        y: -20,
        opacity: 0,
        ease: "back.out(1.7)"
    });
</script>
</body>
</html>
