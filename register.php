<?php
session_start();
require 'connect.php';

if (isset($_SESSION["user_id"])) {
  if ($_SESSION["role"] === "admin") {
    header("Location: /admin/dashboard.php");
  } else {
    header("Location: index.php");
  }
  exit();
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = trim($_POST["name"]);
  $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
  $password = $_POST["password"];

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "❌ Invalid email address.";
  } elseif (strlen($password) < 6) {
    $error = "❌ Password must be at least 6 characters.";
  } else {
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $error = "❌ Email already registered.";
    } else {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $role = "user";
      $insert = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
      $insert->bind_param("ssss", $name, $email, $hashed_password, $role);
      if ($insert->execute()) {
        $success = "✅ Registration successful! Please login.";
      } else {
        $error = "❌ Something went wrong. Try again.";
      }
      $insert->close();
    }
    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register – Burger Hut</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <style>
    * {
      margin: 0; padding: 0; box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      height: 100vh;
      background: url('https://images.unsplash.com/photo-1550317138-10000687a72b') no-repeat center center/cover;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .register-box {
      background: rgba(255, 255, 255, 0.96);
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.25);
      width: 100%;
      max-width: 420px;
      text-align: center;
    }

    .register-box h2 {
      margin-bottom: 20px;
      color: #333;
    }

    .register-box input {
      width: 100%;
      padding: 14px;
      margin: 12px 0;
      border: 1px solid #ccc;
      border-radius: 10px;
      font-size: 16px;
      background: #f9f9f9;
    }

    .register-box button {
      width: 100%;
      padding: 14px;
      background: #d35400;
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }

    .register-box button:hover {
      background: #e67e22;
    }

    .message {
      margin: 15px 0;
      font-size: 14px;
    }

    .message.success {
      color: green;
    }

    .message.error {
      color: red;
    }

    .login-link {
      margin-top: 10px;
      font-size: 14px;
    }

    .login-link a {
      color: #d35400;
      text-decoration: none;
    }

    .login-link a:hover {
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .register-box {
        margin: 0 15px;
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>

  <div class="register-box" id="registerBox">
    <h2>🍔 Join Burger Hut</h2>

    <?php if (!empty($success)): ?>
      <p class="message success"><?= htmlspecialchars($success) ?></p>
    <?php elseif (!empty($error)): ?>
      <p class="message error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email ?? '') ?>" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Register</button>
    </form>

    <p class="login-link">Already have an account? <a href="login.php">Login</a></p>
  </div>

  <script>
    gsap.from("#registerBox", {
      scale: 0.8,
      opacity: 0,
      duration: 1.2,
      ease: "elastic.out(1, 0.5)"
    });
  </script>

</body>
</html>
