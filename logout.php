<?php
session_start();
session_unset();
session_destroy();

// Redirect to home page with logout message
header("Location: index.php?logout=success");
exit();
