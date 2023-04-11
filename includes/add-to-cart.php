<?php
include_once '../config/db_connection.php';
include_once '../classes/cart.class.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request.'));
    exit();
}

// Get the product ID and quantity from the request
$product_id = $_POST['product-id'];
$quantity = $_POST['order-quantity'];

if ($quantity <= 0) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid order quantity.'));
    exit();
}

// Initialize the cart object and get the user's cart ID
$cart = new Cart($conn);
$cart_data = $cart->select($_SESSION['user_id']);
$cart_id = $cart_data['id'];

// Check if the product is already in the cart
$cart_items = $cart->getCartItems($cart_id)['cart_items'];
$existing_cart_item = null;
foreach ($cart_items as $cart_item) {
    if ($cart_item['product_id'] == $product_id) {
        $existing_cart_item = $cart_item;
        break;
    }
}

// If the product is already in the cart, update the quantity
if ($existing_cart_item) {
    $new_quantity = $existing_cart_item['quantity'] + $quantity;
    $cart->updateCartItem($existing_cart_item['id'], $new_quantity);
} else {
    // Add the new item to the cart
    $cart->addItem($cart_id, $product_id, $quantity);
}

http_response_code(200);
exit();
?>
