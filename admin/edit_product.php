<?php
session_start();
include '../connect.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = intval($_GET['id']);
$query = "SELECT * FROM products WHERE id = $id LIMIT 1";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "Product not found.";
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = floatval($_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Handle new image
    $imageName = $product['image'];
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image'];
        $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (in_array($ext, $allowed)) {
            $imageName = uniqid() . '.' . $ext;
            move_uploaded_file($image['tmp_name'], "../uploads/" . $imageName);
        } else {
            $message = "Only JPG and PNG images allowed.";
        }
    }

    if (!$message) {
        $updateQuery = "UPDATE products SET 
                        name='$name', category='$category', price='$price', 
                        description='$description', image='$imageName'
                        WHERE id = $id";
        if (mysqli_query($conn, $updateQuery)) {
            header("Location: products.php?updated=1");
            exit();
        } else {
            $message = "Error updating product: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product - Burger Hut</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f1f5f9;
    }

    .form-container {
      max-width: 700px;
      margin: auto;
      background: #ffffff;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
      margin-top: 40px;
    }

    .form-title {
      text-align: center;
      margin-bottom: 2rem;
      color: #0f172a;
    }

    .btn-update {
      background-color: #3b82f6;
      color: #fff;
    }

    .btn-update:hover {
      background-color: #2563eb;
    }

    .back-link {
      text-decoration: none;
      color: #1d4ed8;
    }

    .preview-img {
      max-width: 100px;
      margin-top: 5px;
      border-radius: 6px;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="form-container">
    <h3 class="form-title">✏️ Edit Product</h3>

    <?php if ($message): ?>
      <div class="alert alert-danger"><?= $message ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Product Name:</label>
        <input type="text" name="name" class="form-control" value="<?= $product['name'] ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Category:</label>
        <input type="text" name="category" class="form-control" value="<?= $product['category'] ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Price (USD):</label>
        <input type="number" name="price" class="form-control" step="0.01" value="<?= $product['price'] ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Description:</label>
        <textarea name="description" class="form-control" rows="4" required><?= $product['description'] ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Current Image:</label><br>
        <img src="../uploads/<?= $product['image'] ?>" class="preview-img">
      </div>

      <div class="mb-3">
        <label class="form-label">Change Image (Optional):</label>
        <input type="file" name="image" class="form-control" accept="image/png, image/jpeg">
      </div>

      <button type="submit" class="btn btn-update w-100">Update Product</button>
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
