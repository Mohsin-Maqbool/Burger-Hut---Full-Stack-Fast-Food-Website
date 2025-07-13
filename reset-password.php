<?php
require 'connect.php';
session_start();

$token = $_GET['token'] ?? '';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $new_password = $_POST['password'] ?? '';
    $hashed = password_hash($new_password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expiry >= NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id);
        $stmt->fetch();

        $update = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
        $update->bind_param("si", $hashed, $user_id);
        $update->execute();

        $success = "✅ Password successfully reset. <a href='login.php'>Login Now</a>";
    } else {
        $error = "❌ Invalid or expired token.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #1e3c72, #2a5298);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #333;
    }

    .box {
      background: #fff;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
      max-width: 420px;
      width: 100%;
      text-align: center;
      opacity: 0;
      transform: translateY(30px);
    }

    h2 {
      margin-bottom: 20px;
      color: #d35400;
      font-size: 24px;
    }

    input[type="password"] {
      width: 100%;
      padding: 14px;
      margin: 12px 0;
      border: 1px solid #ccc;
      border-radius: 10px;
      font-size: 16px;
    }

    button {
      padding: 14px 25px;
      background: #d35400;
      border: none;
      color: white;
      border-radius: 10px;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s ease;
    }

    button:hover {
      background: #e67e22;
      transform: scale(1.05);
    }

    .msg {
      margin-top: 15px;
      font-size: 15px;
    }

    a {
      color: #2980b9;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
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
    <h2>🔑 Reset Your Password</h2>

    <?php if ($error): ?><p class="msg" style="color:red"><?= $error ?></p><?php endif; ?>
    <?php if ($success): ?><p class="msg" style="color:green"><?= $success ?></p><?php endif; ?>

    <?php if (!$success): ?>
    <form method="POST">
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
      <input type="password" name="password" placeholder="Enter new password" required>
      <button type="submit">Reset Password</button>
    </form>
    <?php endif; ?>
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
