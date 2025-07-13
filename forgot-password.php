<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php'; // PHPMailer autoload

require 'connect.php';
session_start();

$message = '';
$type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if user exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Save token in DB
        $update = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $update->bind_param("sss", $token, $expiry, $email);
        $update->execute();

        $reset_link = "http://localhost/burgerhut_site/reset-password.php?token=" . $token;
        $subject = "🔑 Reset Your Password - Burger Hut";
        $body = "Click the link to reset your password:\n\n$reset_link\n\nThis link will expire in 1 hour.";

        // Send email
       $mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'mohsin.dev182@gmail.com';       // ✅ Your Gmail
    $mail->Password   = 'your-app-password-here';        // ✅ 16-digit App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('mohsin.dev182@gmail.com', 'Burger Hut');
    $mail->addAddress($email);
    $mail->isHTML(false); // Since you're using plain text body
    $mail->Subject = $subject;
    $mail->Body    = $body;

    $mail->send();
    $message = "✅ Reset link sent to your email.";
    $type = "success";
} catch (Exception $e) {
    $message = "❌ Mailer Error: {$mail->ErrorInfo}";
    $type = "error";
}

    } else {
        $message = "❌ Email not found.";
        $type = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #1e3c72, #2a5298);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }
    .box {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(15px);
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 15px 35px rgba(0,0,0,0.2);
      max-width: 400px;
      width: 100%;
      text-align: center;
      color: #fff;
      opacity: 0;
      transform: translateY(30px);
    }
    h2 {
      margin-bottom: 20px;
      font-size: 26px;
      color: #f39c12;
    }
    input[type="email"] {
      width: 100%;
      padding: 14px;
      margin: 12px 0;
      border-radius: 10px;
      border: none;
      font-size: 16px;
    }
    button {
      padding: 14px 25px;
      background: #f39c12;
      border: none;
      color: white;
      border-radius: 10px;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s ease;
    }
    button:hover {
      background: #f1c40f;
      transform: scale(1.05);
    }
    .msg {
      margin-top: 15px;
      font-size: 15px;
      animation: fadeIn 0.8s ease forwards;
    }
    .success { color: #00ff9c; }
    .error { color: #ff6961; }
    a {
      color: #ffd700;
      word-break: break-word;
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    @media (max-width: 480px) {
      .box {
        margin: 20px;
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>
  <div class="box">
    <h2>🔐 Forgot Password</h2>
    <?php if (!empty($message)) echo "<p class='msg $type'>$message</p>"; ?>
    <form method="POST">
      <input type="email" name="email" placeholder="Enter your email" required>
      <button type="submit">Send Reset Link</button>
    </form>
  </div>

  <script>
    gsap.to(".box", {
      y: 0,
      opacity: 1,
      duration: 1,
      ease: "power2.out"
    });
  </script>
</body>
</html>
