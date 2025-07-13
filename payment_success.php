<?php
session_start();

// Optional: Clear cart after successful payment
unset($_SESSION['cart']);

// Optional: Get name or details if stored
$name = $_SESSION['customer_name'] ?? 'Valued Customer';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Successful – Burger Hut</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #e9f5ec;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .success-box {
      background: white;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      text-align: center;
    }
    h1 {
      color: #27ae60;
    }
    p {
      font-size: 18px;
      margin: 20px 0;
    }
    a {
      text-decoration: none;
      color: white;
      background: #27ae60;
      padding: 12px 24px;
      border-radius: 8px;
      display: inline-block;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="success-box">
    <h1>🎉 Payment Successful!</h1>
    <p>Thank you, <?= htmlspecialchars($name) ?>. Your order has been received.</p>
    <a href="index.php">Back to Home</a>
  </div>
</body>
</html>
