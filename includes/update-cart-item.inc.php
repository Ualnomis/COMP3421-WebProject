<?php
include_once '../config/db_connection.php';
include_once('../classes/cart.class.php');

if (!isset($_SESSION['user_id'])) {
    echo '<script>window.location.replace("../public/");</script>';
    exit;
} else if ($_SESSION['role'] === 'seller') {
    echo '<script>window.location.replace("../public/");</script>';
    exit;
} else if ($_SESSION['role'] === 'buyer') {

}

// Create a new instance of the Cart class with a database connection
$cart = new Cart($conn);

// Get the data from the AJAX request
$data = json_decode(file_get_contents('php://input'), true);

// Update the quantity in the database
$cartItemId = $data['cart_item_id'];
$newQuantity = $data['quantity'];
$result = $cart->updateCartItem($cartItemId, $newQuantity);

header('Content-Type: application/json');
if ($result) {
    // Get the updated sum price and total price
    $user_id = $_SESSION['user_id']; // Assumes user is logged in
    $cart_data = $cart->select($user_id);
    $cart_id = $cart_data['id'];
    $cart_items = $cart->getCartItems($cart_id)['cart_items'];
    $total_price = 0;
    $new_sum_price = 0;
    foreach ($cart_items as $cart_item) {
        if ($cart_item['id'] == $cartItemId) {
            $new_sum_price = $cart_item['sum_price'];
        }
    }
    echo json_encode([
        'success' => true,
        'new_sum_price' => $new_sum_price,
        'new_total_price' => $cart->getCartItems($cart_id)['total_sum_price'],
    ]);
} else {
    echo json_encode(['success' => false]);
}
?>