<?php
session_start();
require 'connect.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Stripe Setup
\Stripe\Stripe::setApiKey('sk_test_your_key_here');

// 1. Validate session ID
if (!isset($_GET['session_id'])) {
    die("Invalid session.");
}
$session_id = $_GET['session_id'];

// 2. Prevent Duplicate Entry
$stmt = $conn->prepare("SELECT id FROM orders WHERE session_id = ?");
$stmt->bind_param("s", $session_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    header("Location: index.php"); // Already processed
    exit;
}

// 3. Fetch Stripe session
try {
    $session = \Stripe\Checkout\Session::retrieve($session_id);
    if ($session->payment_status !== 'paid') {
        die("Payment not completed.");
    }

    $name    = $session->metadata->name ?? 'Guest';
    $email   = $session->customer_details->email ?? 'noemail@example.com';
    $phone   = $session->metadata->phone ?? '';
    $address = $session->metadata->address ?? '';
    $total   = (float)($session->metadata->total_pkr ?? 0);
    $cart    = json_decode($session->metadata->cart ?? '[]', true);
} catch (Exception $e) {
    die("Stripe error: " . $e->getMessage());
}

$created_at = date('Y-m-d H:i:s');
$payment_status = 'paid';
$status = 'pending';

// 4. Save Order
$stmt = $conn->prepare("INSERT INTO orders (session_id, customer_name, customer_email, total_amount, created_at, customer_phone, customer_address, payment_status, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssdsisss", $session_id, $name, $email, $total, $created_at, $phone, $address, $payment_status, $status);
if (!$stmt->execute()) {
    die("Order save failed: " . $stmt->error);
}
$order_id = $stmt->insert_id;

// 5. Save Order Items
foreach ($cart as $item) {
    $product = $item['name'] ?? '';
    $price   = (float)($item['price'] ?? 0);
    $qty     = (int)($item['qty'] ?? 1);
    $subtotal = $price * $qty;

    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_name, price, quantity, subtotal) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isddi", $order_id, $product, $price, $qty, $subtotal);
    $stmt->execute();
}

// 6. Send Admin Email
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your_admin_email@gmail.com';
    $mail->Password = 'your_app_password'; // Use App Password, not Gmail password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('your_admin_email@gmail.com', 'Burger Hut');
    $mail->addAddress('admin@burgerhut.com');

    $mail->isHTML(true);
    $mail->Subject = '🔔 New Order – Burger Hut';
    $mail->Body = "
        <h2>New Order Received</h2>
        <p><strong>Name:</strong> {$name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Phone:</strong> {$phone}</p>
        <p><strong>Total:</strong> PKR " . number_format($total) . "</p>
        <p><strong>Order Time:</strong> {$created_at}</p>
        <p><a href='https://youradminpanel.com/orders.php'>View in Admin Panel</a></p>
    ";
    $mail->send();
} catch (Exception $e) {
    error_log("Email error: " . $mail->ErrorInfo);
}

// 7. WhatsApp Link
$formattedPhone = preg_replace('/^0/', '92', $phone);
$msg = rawurlencode("🍔 Hi $name! Your order has been received by *Burger Hut*.\n📍 Address: $address\n💵 Total: PKR " . number_format($total));
$whatsapp_url = "https://api.whatsapp.com/send?phone=$formattedPhone&text=$msg";

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Successful – Burger Hut</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; }
    body {
      margin: 0;
      padding: 20px;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #f2fff0, #e0ffe9);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .container {
      background: #fff;
      padding: 40px 30px;
      max-width: 500px;
      width: 100%;
      text-align: center;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    h1 {
      font-size: 28px;
      color: #2ecc71;
      margin-bottom: 10px;
    }
    p {
      font-size: 16px;
      margin-bottom: 25px;
      color: #555;
    }
    .btn {
      display: inline-block;
      margin: 10px 5px;
      padding: 12px 25px;
      background-color: #27ae60;
      color: white;
      border: none;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: 0.3s ease;
    }
    .btn:hover {
      background-color: #219150;
    }
    @media (max-width: 600px) {
      .container { padding: 25px 20px; }
      .btn { width: 100%; margin: 10px 0; }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>🎉 Thank you, <?= htmlspecialchars($name) ?>!</h1>
    <p>Your order has been placed successfully. We’ll contact you shortly.</p>
    <a href="index.php" class="btn">🏠 Back to Homepage</a>
    <a href="<?= htmlspecialchars($whatsapp_url) ?>" class="btn" target="_blank">📲 Notify on WhatsApp</a>
  </div>
</body>
</html>
