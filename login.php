<?php
setcookie("remember_me", "", time() - 3600, "/");

session_start();
require 'connect.php';

// Auto-login via cookie
if (!isset($_SESSION["user_id"]) && isset($_COOKIE['remember_me'])) {
  list($user_id, $user_name, $role) = explode('|', $_COOKIE['remember_me']);
  $_SESSION["user_id"] = $user_id;
  $_SESSION["user_name"] = $user_name;
  $_SESSION["role"] = $role;

  if ($role === 'admin') {
    header("Location: /burgerhut_site/admin/dashboard.php");
  } else {
    header("Location: /burgerhut_site/index.php");
  }
  exit();
}

// Redirect if already logged in
$current_page = basename($_SERVER['PHP_SELF']);
if (isset($_SESSION["user_id"])) {
  if ($_SESSION["role"] === 'admin' && $current_page !== 'dashboard.php') {
    header("Location: /burgerhut_site/admin/dashboard.php");
    exit();
  } elseif ($_SESSION["role"] !== 'admin' && $current_page !== 'index.php') {
    header("Location: /burgerhut_site/index.php");
    exit();
  }
}

$error = "";
$remember_checked = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = trim($_POST["email"]);
  $password = $_POST["password"];
  $login_as = $_POST["login_as"] ?? "user"; // admin or user
  $remember = isset($_POST["remember"]);
  $remember_checked = $remember ? "checked" : "";

  $sql = "SELECT id, name, password, role FROM users WHERE email = ? AND role = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $email, $login_as);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows === 1) {
    $stmt->bind_result($id, $name, $hashed_password, $role);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
      $_SESSION["user_id"] = $id;
      $_SESSION["user_name"] = $name;
      $_SESSION["role"] = $role;

      if ($remember) {
        $cookie_value = "$id|$name|$role";
        setcookie("remember_me", $cookie_value, [
          'expires' => time() + (86400 * 7),
          'path' => '/',
          'secure' => isset($_SERVER['HTTPS']),
          'httponly' => true,
          'samesite' => 'Strict'
        ]);
      }

      if ($role === 'admin') {
        header("Location: /burgerhut_site/admin/dashboard.php");
      } else {
        header("Location: /burgerhut_site/index.php");
      }
      exit();
    } else {
      $error = "❌ Invalid password.";
      $reason = "Invalid password";
    }
  } else {
    $error = "❌ No $login_as account found with that email.";
    $reason = "$login_as email not found";
  }

  // Log failed login attempt
  if (!empty($reason)) {
    $log_sql = "INSERT INTO login_attempts (email, reason) VALUES (?, ?)";
    $log_stmt = $conn->prepare($log_sql);
    $log_stmt->bind_param("ss", $email, $reason);
    $log_stmt->execute();
    $log_stmt->close();
  }

  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login – Burger Hut</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Roboto', sans-serif; }
    body {
      height: 100vh;
      background: url('https://images.unsplash.com/photo-1550317138-10000687a72b?auto=format&fit=crop&w=1740&q=80') no-repeat center center/cover;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-box {
      background: rgba(255, 255, 255, 0.95);
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }
    h2 { margin-bottom: 20px; color: #222; }
    input[type="email"], input[type="password"], select {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: none;
      border-radius: 8px;
      background: #f0f0f0;
      font-size: 16px;
    }
    button {
      width: 100%;
      padding: 12px;
      background: #d35400;
      color: white;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
    }
    button:hover { background: #e67e22; }
    .error { color: red; margin-top: 10px; }
    .register-link { margin-top: 10px; font-size: 14px; }
    .register-link a { color: #d35400; text-decoration: none; }
    .register-link a:hover { text-decoration: underline; }
    label.remember {
      display: flex;
      align-items: center;
      margin: 8px 0;
      font-size: 14px;
      justify-content: flex-start;
    }
    label.remember input { margin-right: 8px; }
  </style>
</head>
<body>
  <div class="login-box" id="loginBox">
    <h2>🍔 Welcome to Burger Hut</h2>
    <?php if (!empty($error)): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form action="login.php" method="POST">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <select name="login_as" required>
        <option value="user">Login as User</option>
        <option value="admin">Login as Admin</option>
      </select>
      <label class="remember">
        <input type="checkbox" name="remember" <?= $remember_checked ?>> Remember Me
      </label>
      <div style="text-align: right; margin: 5px 0;">
        <a href="forgot-password.php" style="font-size: 13px; color: #d35400; text-decoration: none;">Forgot Password?</a>
      </div>
      <button type="submit">Login</button>
    </form>
    <p class="register-link">Don't have an account? <a href="register.php">Register here</a>.</p>
  </div>

  <script>
    gsap.from("#loginBox", {
      y: -100,
      opacity: 0,
      duration: 1,
      ease: "bounce"
    });
  </script>
</body>
</html>
