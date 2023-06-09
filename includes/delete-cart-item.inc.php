<?php
require_once '../config/db_connection.php';
require_once '../classes/cart.class.php';

if (!isset($_SESSION['user_id'])) {
    echo '<script>window.location.replace("../public/");</script>';
    exit;
} else if ($_SESSION['role'] === 'seller') {
    echo '<script>window.location.replace("../public/");</script>';
    exit;
} else if ($_SESSION['role'] === 'buyer') {

}

$cart = new Cart($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_item_id'])) {
    $cartItemId = intval($_POST['cart_item_id']);
    $success = $cart->deleteCartItem($cartItemId);
    $status = $success ? 'success' : 'error';
    header("Location: ../public/cart-detail.php?status=$status");
    exit;
}

header('Location: ../public/cart-detail.php');
exit;
?>