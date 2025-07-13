<?php
session_start();

// Redirect if cart is empty
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    header("Location: cart.php?empty=1");
    exit;
}

$cart = $_SESSION['cart'];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['qty'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout – Burger Hut</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 20px;
      background: linear-gradient(to right, #fceabb, #f8b500);
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
    }

    .checkout-container {
      background: #fff;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 800px;
      animation: fadeIn 1s ease-in-out;
    }

    h2 {
      margin-top: 0;
      color: #d35400;
      text-align: center;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
    }

    th, td {
      text-align: left;
      padding: 10px;
      border-bottom: 1px solid #ccc;
    }

    th {
      background-color: #e67e22;
      color: white;
    }

    .total {
      font-size: 20px;
      font-weight: bold;
      text-align: right;
      margin-bottom: 20px;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    input[type="text"], input[type="email"], input[type="tel"] {
      padding: 12px;
      border: 2px solid #e67e22;
      border-radius: 6px;
      font-size: 16px;
    }

    button {
      background: #e67e22;
      color: white;
      padding: 14px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      font-weight: bold;
      transition: background 0.3s;
    }

    button:hover {
      background: #d35400;
    }

    @media (max-width: 600px) {
      table th, table td {
        font-size: 13px;
        padding: 8px;
      }

      input, button {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>

<div class="checkout-container">
  <h2>🧾 Checkout</h2>

  <table id="order-summary">
    <thead>
      <tr>
        <th>Product</th>
        <th>Qty</th>
        <th>Price (PKR)</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($cart as $item): ?>
      <tr>
        <td><?= htmlspecialchars($item['name']) ?></td>
        <td><?= $item['qty'] ?></td>
        <td><?= number_format($item['price'], 2) ?></td>
        <td><?= number_format($item['qty'] * $item['price'], 2) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="total">Total: PKR <?= number_format($total, 2) ?></div>

  <form action="create_checkout_session.php" method="POST" id="checkout-form">
    <input type="text" name="name" required placeholder="Your Name">
    <input type="email" name="email" required placeholder="Your Email">
    <input type="tel" name="phone" required placeholder="Phone (e.g. 923xxxxxxxxx)">
    <input type="text" name="address" required placeholder="Delivery Address">
    <button type="submit">Pay with Stripe</button>
  </form>
</div>

<script>
  gsap.from(".checkout-container", {
    duration: 1,
    opacity: 0,
    y: 40,
    ease: "power2.out"
  });

  gsap.from("#order-summary tbody tr", {
    duration: 1,
    opacity: 0,
    y: 30,
    stagger: 0.2,
    ease: "power2.out"
  });

  gsap.from("#checkout-form input, #checkout-form button", {
    duration: 1,
    opacity: 0,
    y: 20,
    stagger: 0.15,
    ease: "power2.out"
  });
</script>

</body>
</html>
