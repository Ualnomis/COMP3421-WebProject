<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include necessary files
include_once('../classes/cart.class.php');
include_once '../config/db_connection.php';

// Create a new instance of the Cart class with a database connection
$cart = new Cart($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['clear_cart_id'])) {
    // Get cart item ID from the form
    $cart_id = intval($_POST['clear_cart_id']);
    print_r("cart_id : "+ $cart_id);
    // Call the deleteCartItem method to remove the item from the cart
    $result = $cart->removeAllCartItem($cart_id);

    if ($result) {
        // If the item was successfully deleted, redirect back to the cart detail page
        header('Location: cart-detail.php?status=success');
    } else {
        // If there was an error, redirect back to the cart detail page with an error status
        header('Location: cart-detail.php?status=error');
    }
} else {
    // If the request method is not POST or cart_item_id is not set, redirect back to the cart detail page
    header('Location: cart-detail.php');
}
?>
