<?php
// Sample DB connection file

$host = 'localhost';
$user = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

$conn = mysqli_connect($host, $user, $password, $dbname);

// Optional: Add connection error message
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


// This is a sample DB connection file.
// After cloning this project, rename this file to connect.php
// and fill in your actual database credentials.
