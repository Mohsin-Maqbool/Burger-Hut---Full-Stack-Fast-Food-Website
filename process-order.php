<?php
// Database connection
$host = 'localhost';
$user = 'root';
$pass = ''; // Change if needed
$dbname = 'burgerhut_site';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// Get and sanitize input
$name = htmlspecialchars(trim($_POST['name']));
$phone = htmlspecialchars(trim($_POST['phone']));
$message = htmlspecialchars(trim($_POST['message'] ?? ''));

// Save to database
$stmt = $conn->prepare("INSERT INTO orders (name, phone, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $phone, $message);
$stmt->execute();
$stmt->close();

// Optional: Generate WhatsApp link to return to frontend
$whatsappMessage = "Hello! My name is $name. Phone: $phone. Message: $message";
$encodedMessage = urlencode($whatsappMessage);
$whatsappLink = "https://wa.me/923014486345?text=$encodedMessage";

// Return response with link
echo json_encode([
    "status" => "success",
    "msg" => "Thank you, $name! Your message has been sent. We'll respond shortly.",
    "whatsapp" => $whatsappLink
]);
?>
