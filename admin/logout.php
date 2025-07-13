<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy session
session_destroy();

// Clear "remember me" cookie if set
if (isset($_COOKIE['remember_me'])) {
    setcookie("remember_me", "", time() - 3600, "/");
}

// Redirect to login page with logout message
header("Location: /burgerhut_site/login.php?message=logged_out");
exit();
?>
