<?php
include_once '../config/db_connection.php';
require_once '../classes/cart.class.php';

$cart = new Cart($conn);

if (isset($_SESSION['role'], $_SESSION['user_id']) && $_SESSION['role'] === 'buyer') {
    $cart_id = $cart->select($_SESSION['user_id'])['id'];
    $count = $cart->countItems($cart_id);
    $user_id = $_SESSION['user_id']; // Assumes user is logged in
    $cart_items = $cart->getCartItems($cart_id)['cart_items'];
    echo json_encode($cart_items);
}
