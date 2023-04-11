<?php
include_once '../config/db_connection.php';
require_once '../classes/cart.class.php';

$cart = new Cart($conn);

if (isset($_SESSION['role'], $_SESSION['user_id']) && $_SESSION['role'] === 'buyer') {
    $cart_id = $cart->select($_SESSION['user_id'])['id'];
    $count = $cart->countItems($cart_id);
    echo $count;
}
