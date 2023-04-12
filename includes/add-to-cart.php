<?php
include_once '../config/db_connection.php';
include_once '../classes/cart.class.php';

function send_error_response($code, $message)
{
    http_response_code($code);
    echo json_encode(array('error' => $message));
    exit();
}

function is_buyer()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'buyer';
}
function is_login()
{
    return isset($_SESSION['role']);
}

function is_valid_request()
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

if (!is_valid_request()) {
    send_error_response(400, 'Invalid request.');
} else if (!is_login()) {
    send_error_response(400, 'Please Login to add product to cart.');
}else if (!is_buyer()) {
    send_error_response(400, 'Only buyer can add to cart.');
}

$product_id = $_POST['product-id'];
$quantity = $_POST['order-quantity'];

if ($quantity <= 0) {
    send_error_response(400, 'Invalid order quantity.');
}

$cart = new Cart($conn);
$cart_data = $cart->select($_SESSION['user_id']);
$cart_id = $cart_data['id'];
$existing_cart_item = $cart->find_cart_item($cart_id, $product_id);

if ($existing_cart_item) {
    $new_quantity = $existing_cart_item['quantity'] + $quantity;

    if ($new_quantity <= $existing_cart_item['remain_quantity']) {
        $add_cart_result = $cart->updateCartItem($existing_cart_item['id'], $new_quantity);
    } else {
        send_error_response(400, 'Not enough quantity to add.');
    }
} else {
    $add_cart_result = $cart->addItem($cart_id, $product_id, $quantity);
}

if ($add_cart_result > 0) {
    http_response_code(200);
    exit();
} else {
    send_error_response(400, 'Fail to add cart.');
}
?>