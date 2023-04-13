<?php
include_once '../config/db_connection.php';
require_once '../classes/order.class.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
} else if ($_SESSION['role'] === 'buyer') {
    echo json_encode(['success' => false, 'message' => 'Invalid Request']);
    exit;
}

$order = new Order($conn);

// Check if the required data is available in the POST request
if (!isset($_POST['order_id']) || !isset($_POST['order_status'])) {
    echo json_encode(['success' => false, 'message' => 'Required data is missing']);
    exit;
}

$order_id = intval($_POST['order_id']);
$order_status = intval($_POST['order_status']);

// Fetch the order by ID to get the current details
$current_order = $order->get_order_by_id($order_id);
if (!$current_order) {
    echo json_encode(['success' => false, 'message' => 'Order not found']);
    exit;
}

// Update the order status
$affected_rows = $order->update_order($order_id, $current_order['buyer_name'], $current_order['buyer_phone'], $current_order['buyer_address'], $order_status);

if ($affected_rows >= 0) {
    echo json_encode(['success' => true, 'message' => 'Order status updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error updating order status']);
}
?>
