<?php
// ✅ Safe session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get limit from query string, default is 3
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 3;

// ✅ Safe default if needed
$limit = max(1, $limit);

// Prepare SQL with static IDs (no parameter binding required)
$sql = "SELECT * FROM products WHERE id IN (1, 2, 3) ORDER BY id ASC";

// ✅ Use direct query since no dynamic parameters
$result = mysqli_query($conn, $sql);

// ✅ Optional: if you want dynamic LIMIT (instead of fixed IDs), use this:
// $sql = "SELECT * FROM products ORDER BY id DESC LIMIT $limit";
// $result = mysqli_query($conn, $sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Products – Burger Hut</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <style>
    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #ffe0c3, #ffc1a1);
      padding: 40px;
    }

    h1 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 50px;
      font-size: 36px;
    }

    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 30px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .product-card {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      display: flex;
      flex-direction: column;
    }

    .product-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .product-image {
      overflow: hidden;
    }

    .product-card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .product-card:hover img {
      transform: scale(1.05);
    }

    .product-card h3 {
      margin: 15px;
      color: #e67e22;
      font-size: 20px;
    }

    .product-card p {
      margin: 0 15px 10px;
      color: #555;
      font-size: 15px;
    }

    .product-card .category {
      font-size: 13px;
      color: #888;
      margin: 0 15px 10px;
      font-style: italic;
    }

    .product-card .price {
      margin: 0 15px 10px;
      font-weight: bold;
      color: #2c3e50;
    }

    .product-card .btn {
      display: block;
      margin: 15px;
      padding: 10px;
      text-align: center;
      background-color: #e67e22;
      color: white;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s ease;
    }

    .product-card .btn:hover {
      background-color: #d35400;
    }

    .no-products {
      text-align: center;
      color: #999;
      font-size: 18px;
      padding-top: 50px;
    }
  </style>
</head>
<body>

<h1>🍔 Explore Our Delicious Range</h1>

<div class="product-grid" id="products">
  <?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
      <div class="product-card">
        <div class="product-image">
          <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
        </div>
        <h3><?= htmlspecialchars($row['name']) ?></h3>
        <p class="price">PKR <?= number_format($row['price'], 2) ?></p>
        <p><?= htmlspecialchars(substr($row['description'], 0, 80)) ?>...</p>
        <p class="category"><?= htmlspecialchars($row['category']) ?></p>
        <a href="product.php?id=<?= $row['id'] ?>" class="btn">More Details</a>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p class="no-products">No products found. Please check back later.</p>
  <?php endif; ?>
</div>

<script>
  gsap.from("#products .product-card", {
    opacity: 0,
    y: 40,
    stagger: 0.15,
    duration: 1.2,
    ease: "power2.out"
  });
</script>

</body>
</html>
