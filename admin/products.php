<?php
session_start();
include '../connect.php';

// Fetch products
$result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");

// Toast message
$toast = '';
if (isset($_GET['added'])) $toast = 'Product added successfully!';
elseif (isset($_GET['updated'])) $toast = 'Product updated successfully!';
elseif (isset($_GET['deleted'])) $toast = 'Product deleted successfully!';
elseif (isset($_GET['error'])) $toast = 'An error occurred. Please try again.';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Products - Burger Hut</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

  <style>
    body {
      background: #f9fafb;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 2rem 0;
    }

    .card {
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      transition: transform 0.3s;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card-img-top {
      height: 200px;
      object-fit: cover;
      border-top-left-radius: 15px;
      border-top-right-radius: 15px;
    }

    .btn-add {
      background-color: #10b981;
      color: white;
    }

    .btn-add:hover {
      background-color: #059669;
    }

    .card-title {
      font-weight: 600;
    }

    .btn-edit {
      background-color: #3b82f6;
      color: white;
    }

    .btn-edit:hover {
      background-color: #2563eb;
    }

    .btn-delete {
      background-color: #ef4444;
      color: white;
    }

    .btn-delete:hover {
      background-color: #dc2626;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="header">
    <h2>🍔 Manage Products</h2>
    <a href="add_product.php" class="btn btn-add">
      <i class="fas fa-plus-circle"></i> Add Product
    </a>
  </div>

  <div class="row" id="product-list">
    <?php while($row = mysqli_fetch_assoc($result)): ?>
      <div class="col-md-4 mb-4 product-card">
        <div class="card">
          <img src="../uploads/<?= $row['image'] ?>" class="card-img-top" alt="<?= $row['name'] ?>">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
            <p class="card-text"><strong>Category:</strong> <?= htmlspecialchars($row['category']) ?></p>
            <p class="card-text"><strong>Price:</strong> $<?= number_format($row['price'], 2) ?></p>
            <p class="card-text"><?= substr(htmlspecialchars($row['description']), 0, 60) ?>...</p>
            <div class="d-flex justify-content-between">
              <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-edit btn-sm">
                <i class="fas fa-edit"></i> Edit
              </a>
              <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn btn-delete btn-sm" onclick="return confirm('Delete this product?')">
                <i class="fas fa-trash"></i> Delete
              </a>
            </div>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<!-- SweetAlert Toast -->
<?php if ($toast): ?>
<script>
  Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'success',
    title: '<?= $toast ?>',
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true
  });
</script>
<?php endif; ?>

<!-- GSAP Animation -->
<script>
  gsap.from(".product-card", {
    opacity: 0,
    y: 50,
    duration: 0.6,
    stagger: 0.15
  });
</script>

</body>
</html>
