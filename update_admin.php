<?php
require 'connect.php';

$email = 'mohsinmaqbool27@gmail.com';
$newPassword = 'admin123'; // Change this if needed
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$newRole = 'admin';

$sql = "UPDATE users SET password = ?, role = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $hashedPassword, $newRole, $email);

$success = false;
if ($stmt->execute()) {
    $success = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f7f9fc;
    }
    .container {
      margin-top: 100px;
    }
    .card {
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .btn-custom {
      background-color: #007bff;
      color: white;
      transition: 0.3s;
    }
    .btn-custom:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="container d-flex justify-content-center">
    <div class="card p-4 col-md-6">
      <h3 class="text-center mb-4">🔧 Admin Update Status</h3>

      <?php if ($success): ?>
        <div class="alert alert-success">
          ✅ Admin user updated successfully!
        </div>
        <ul class="list-group mb-3">
          <li class="list-group-item">📧 Email: <strong><?= htmlspecialchars($email) ?></strong></li>
          <li class="list-group-item">🔑 Password: <strong><?= htmlspecialchars($newPassword) ?></strong></li>
          <li class="list-group-item">🛡️ Role: <strong><?= htmlspecialchars($newRole) ?></strong></li>
        </ul>
      <?php else: ?>
        <div class="alert alert-danger">
          ❌ Error updating user: <?= $stmt->error ?>
        </div>
      <?php endif; ?>

      <div class="text-center">
        <a href="login.php" class="btn btn-custom">🔙 Go to Login</a>
        <a href="admin/dashboard.php" class="btn btn-success ms-2">📊 Go to Admin Dashboard</a>
      </div>
    </div>
  </div>
</body>
</html>
