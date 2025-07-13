<?php
session_start();
include 'connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: menu.php");
    exit;
}

$product_id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id = $product_id LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Product not found.";
    exit;
}

$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($product['name']) ?> – Burger Hut</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <style>
    * { box-sizing: border-box; }
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #fbd3e9, #bb377d);
      margin: 0;
      padding: 20px;
      color: #333;
    }
    .product-container {
      max-width: 1000px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      align-items: center;
    }
    .product-image {
      flex: 1 1 400px;
      max-width: 100%;
    }
    .product-image img {
      width: 100%;
      height: auto;
      max-height: 400px;
      object-fit: cover;
      border-radius: 15px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
      transition: transform 0.4s ease;
    }
    .product-image img:hover { transform: scale(1.03); }
    .product-details {
      flex: 1 1 400px;
    }
    .product-details h2 {
      color: #e67e22;
      margin-top: 0;
    }
    .product-details p {
      font-size: 16px;
      margin: 10px 0;
    }
    .form-group {
      margin-top: 20px;
    }
    input[type="number"] {
      padding: 10px;
      border: 2px solid #e67e22;
      border-radius: 6px;
      width: 80px;
      margin-right: 10px;
      font-size: 16px;
    }
    .btn {
      background: #e67e22;
      color: white;
      padding: 12px 24px;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      font-size: 16px;
      transition: background 0.3s ease;
    }
    .btn:hover { background: #d35400; }
    @media (max-width: 768px) {
      .product-container {
        flex-direction: column;
        padding: 20px;
      }
      .product-details, .product-image {
        width: 100%;
      }
    }
  </style>
</head>
<body>

<div class="product-container" id="product">
  <div class="product-image">
    <img src="uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
  </div>

  <div class="product-details">
    <h2><?= htmlspecialchars($product['name']) ?></h2>
    <p><strong>Price:</strong> PKR <?= number_format($product['price'], 2) ?></p>
    <p><strong>Category:</strong> <?= htmlspecialchars($product['category']) ?></p>
    <p><?= htmlspecialchars($product['description']) ?></p>

    <form action="add-to-cart.php" method="post" class="form-group">
      <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
      <label for="qty"><strong>Quantity:</strong></label>
      <input type="number" name="qty" id="qty" min="1" value="1" required>
      <button type="submit" class="btn">Add to Cart</button>
    </form>
  </div>
</div>

<script>
  gsap.from("#product", { opacity: 0, y: 50, duration: 1, ease: "power2.out" });
  gsap.from(".product-image img", { scale: 0.9, opacity: 0, delay: 0.3, duration: 1, ease: "back.out(1.7)" });
  gsap.from(".product-details", { x: 100, opacity: 0, delay: 0.5, duration: 1, ease: "power3.out" });
</script>

</body>
</html>
