<?php
require_once '../config/db_connection.php';
require_once('../classes/cart.class.php');
require_once('../classes/order.class.php');

if (!isset($_SESSION['user_id'])) {
    echo '<script>window.location.replace("../public/");</script>';
} else if ($_SESSION['role'] === 'seller') {
    echo '<script>window.location.replace("../public/");</script>';
} else if ($_SESSION['role'] === 'buyer') {

}

$user_id = $_SESSION['user_id'];

$cart = new Cart($conn);
$cart_id = $cart->select($user_id)['id'];
$cart_items = $cart->getCartItems($cart_id)['cart_items'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $cart_items) {
    // Instantiate the Order class
    $order = new Order($conn);

    // Insert a new order and store the returned order ID
    $order_id = $order->insert_order($_SESSION['user_id'], '', '', '', 1);

    // Iterate through the shopping cart items and insert them as order items
    foreach ($cart_items as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        $price = $item['sum_price'];

        // Insert the order item
        $order_item_id = $order->insert_order_item($order_id, $product_id, $quantity, $price);
    }

    // Remove all shopping cart items
    $cart->removeAllCartItem($cart_id);
    echo '<script>window.location.replace("../public/checkout.php?order_id=' . $order_id . '");</script>';
} else {
    echo '<script>window.location.replace("../public/cart-detail.php");</script>';
}


?>