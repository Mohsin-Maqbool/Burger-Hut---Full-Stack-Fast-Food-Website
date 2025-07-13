<?php
session_start();

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    header("Location: cart.php?error=invalid_id");
    exit;
}

$id = (int) $_POST['id'];
$action = $_POST['action'] ?? '';

if (!isset($_SESSION['cart'][$id])) {
    header("Location: cart.php?error=item_not_found");
    exit;
}

switch ($action) {
    case 'update':
        $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
        if ($qty < 1) $qty = 1;
        $_SESSION['cart'][$id]['qty'] = $qty;
        break;

    case 'remove':
        unset($_SESSION['cart'][$id]);
        break;
}

header("Location: cart.php");
exit;
?>
