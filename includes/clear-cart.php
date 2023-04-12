<?php
require_once '../config/db_connection.php';
require_once '../classes/cart.class.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$cart = new Cart($conn);
$cartId = $cart->select($_SESSION['user_id'])['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = $cart->removeAllCartItem($cartId);
    $status = $success ? 'success' : 'error';
    header("Location: ../public/cart-detail.php?status=$status");
    exit;
}

header('Location: ../public/cart-detail.php');
exit;
?>