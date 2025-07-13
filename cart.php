<?php
session_start();
include 'connect.php';
?>

<?php if (isset($_GET['added']) && $_GET['added'] === 'success'): ?>
  <div style="text-align:center; background:#2ecc71; padding:10px; color:white; font-weight:bold;">
    Product successfully added to cart!
  </div>
<?php elseif (isset($_GET['updated'])): ?>
  <div style="text-align:center; background:#3498db; padding:10px; color:white; font-weight:bold;">
    Cart updated successfully.
  </div>
<?php elseif (isset($_GET['removed'])): ?>
  <div style="text-align:center; background:#e74c3c; padding:10px; color:white; font-weight:bold;">
    Item removed from cart.
  </div>
<?php endif; ?>

<?php
// Show empty cart if no items
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Empty Cart - Burger Hut</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background: linear-gradient(to right, #f8c291, #f6e58d);
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .empty-cart {
                text-align: center;
                background: #fff;
                padding: 40px;
                border-radius: 20px;
                box-shadow: 0 10px 20px rgba(0,0,0,0.1);
                max-width: 400px;
            }
            .empty-cart h2 {
                font-size: 24px;
                color: #e74c3c;
                margin-bottom: 20px;
            }
            .empty-cart p {
                font-size: 16px;
                margin-bottom: 30px;
                color: #333;
            }
            .empty-cart a {
                display: inline-block;
                padding: 12px 25px;
                background: #e67e22;
                color: white;
                text-decoration: none;
                border-radius: 8px;
                font-weight: bold;
                transition: background 0.3s;
            }
            .empty-cart a:hover {
                background: #d35400;
            }
            .emoji {
                font-size: 48px;
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <div class="empty-cart">
            <div class="emoji">🛒</div>
            <h2>Your cart is empty</h2>
            <p>Looks like you haven’t added anything yet.</p>
            <a href="index.php#menu">Browse Our Menu</a>
        </div>
    </body>
    </html>
    HTML;
    exit;
}

// Get product details
$ids = implode(",", array_map('intval', array_keys($_SESSION['cart'])));
$sql = "SELECT * FROM products WHERE id IN ($ids)";
$result = $conn->query($sql);

$products = [];
while ($row = $result->fetch_assoc()) {
    $qty = $_SESSION['cart'][$row['id']]['qty'] ?? 1;
    $row['qty'] = $qty;
    $row['total'] = $qty * (float)$row['price'];
    $products[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart - Burger Hut</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #fff5e1;
      padding: 40px;
      margin: 0;
    }
    h1 {
      text-align: center;
      color: #e67e22;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    th, td {
      padding: 16px;
      text-align: center;
      border-bottom: 1px solid #eee;
    }
    th {
      background-color: #e67e22;
      color: white;
    }
    img {
      width: 60px;
      height: 60px;
      border-radius: 8px;
      object-fit: cover;
    }
    .btn {
      padding: 6px 12px;
      background: #e67e22;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .btn:hover {
      background: #d35400;
    }
    .checkout {
      text-align: right;
      margin-top: 20px;
    }
    .checkout a {
      background: green;
      color: white;
      padding: 12px 20px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
    }
    .checkout a:hover {
      background: darkgreen;
    }
    input[type="number"] {
      padding: 5px;
      border-radius: 5px;
      border: 1px solid #ccc;
      width: 60px;
      margin-right: 5px;
    }
  </style>
</head>
<body>

<h1>🛒 Your Cart</h1>

<table id="cart-table">
  <thead>
    <tr>
      <th>Image</th>
      <th>Product</th>
      <th>Price</th>
      <th>Qty</th>
      <th>Total</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $grand_total = 0;
    foreach ($products as $item):
        $grand_total += $item['total'];
    ?>
    <tr>
      <td><img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>"></td>
      <td><?= htmlspecialchars($item['name']) ?></td>
      <td>PKR <?= number_format($item['price'], 1) ?></td>
      <td>
        <form action="cart-actions.php" method="post" style="display:inline-flex;">
          <input type="hidden" name="id" value="<?= $item['id'] ?>">
          <input type="hidden" name="action" value="update">
          <input type="number" name="qty" value="<?= $item['qty'] ?>" min="1">
          <button type="submit" class="btn">Update</button>
        </form>
      </td>
      <td>PKR <?= number_format($item['total'], 1) ?></td>
      <td>
        <form action="cart-actions.php" method="post">
          <input type="hidden" name="id" value="<?= $item['id'] ?>">
          <input type="hidden" name="action" value="remove">
          <button type="submit" class="btn" style="background: crimson;">Remove</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="checkout">
  <h3>Total: PKR <?= number_format($grand_total, 1) ?></h3>
  <a href="checkout.php">Proceed to Checkout</a>
</div>

<script>
  gsap.from("#cart-table tbody tr", {
    opacity: 0,
    y: 40,
    duration: 1,
    stagger: 0.2,
    ease: "power2.out"
  });
</script>

</body>
</html>
