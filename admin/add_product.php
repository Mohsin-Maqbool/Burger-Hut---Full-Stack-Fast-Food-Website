<?php
session_start();
include '../connect.php'; // Your DB connection

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = floatval($_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Handle Image Upload
    $imageName = '';
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image'];
        $allowed = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $imageName = uniqid() . '.' . $ext;
            move_uploaded_file($image['tmp_name'], "../uploads/" . $imageName);
        } else {
            $message = "Invalid image format (Only JPG/PNG allowed)";
        }
    }

    if (!$message) {
        $query = "INSERT INTO products (name, category, price, description, image) 
                  VALUES ('$name', '$category', '$price', '$description', '$imageName')";
        if (mysqli_query($conn, $query)) {
            header("Location: products.php?success=1");
            exit();
        } else {
            $message = "Error adding product: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product - Burger Hut</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8fafc;
    }

    .form-container {
      max-width: 700px;
      margin: auto;
      background: #fff;
      padding: 2rem;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
      border-radius: 10px;
      margin-top: 40px;
    }

    .form-title {
      text-align: center;
      margin-bottom: 2rem;
      color: #1e3a8a;
    }

    .btn-submit {
      background-color: #10b981;
      color: #fff;
    }

    .btn-submit:hover {
      background-color: #059669;
    }

    .back-link {
      text-decoration: none;
      color: #2563eb;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="form-container">
    <h3 class="form-title">🍔 Add New Product</h3>

    <?php if ($message): ?>
      <div class="alert alert-danger"><?= $message ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="name" class="form-label">Product Name:</label>
        <input type="text" name="name" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="category" class="form-label">Category:</label>
        <input type="text" name="category" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="price" class="form-label">Price (USD):</label>
        <input type="number" name="price" step="0.01" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Description:</label>
        <textarea name="description" class="form-control" rows="4" required></textarea>
      </div>

      <div class="mb-3">
        <label for="image" class="form-label">Product Image (JPG/PNG):</label>
        <input type="file" name="image" accept="image/png, image/jpeg" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-submit w-100">Add Product</button>
    </form>

    <div class="mt-3 text-center">
      <a href="products.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Products</a>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/a2e0f2f2c7.js" crossorigin="anonymous"></script>
</body>
</html>
