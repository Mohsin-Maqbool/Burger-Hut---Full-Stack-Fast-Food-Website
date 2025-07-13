<?php
// Make sure this file is placed where your database connection file is available

require 'connect.php'; // Replace with your DB connection file name if different

// Admin credentials
$name = 'Mohsin Maqbool';
$email = 'mohsinmaqbool27@gmail.com';
$password = 'admin123'; // Set any password you want
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$role = 'admin';

// Insert query
$sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

if ($stmt->execute()) {
    echo "✅ Admin user created successfully!<br>";
    echo "👉 Email: <b>$email</b><br>";
    echo "🔑 Password: <b>$password</b><br>";
} else {
    echo "❌ Error: " . $stmt->error;
}
?>
