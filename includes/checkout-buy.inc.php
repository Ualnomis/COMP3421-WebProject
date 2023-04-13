<?php
require_once '../config/db_connection.php';
require_once('../classes/order.class.php');

verify_user_session();
handle_order_request($conn);

function verify_user_session()
{
    if (!isset($_SESSION['user_id'])) {
        send_error_response(400, 'Please Login to Buy.');
    }
}

function handle_order_request($conn)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (is_buyer() && is_valid_order_quantity()) {
            process_order($conn);
        } else {
            send_error_response(400, 'Invalid Request.');
        }
    }
}

function is_buyer()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'buyer';
}

function is_valid_order_quantity()
{
    return isset($_POST['order-quantity']) && $_POST['order-quantity'] > 0;
}

function process_order($conn)
{
    $order = new Order($conn);
    $order_id = $order->insert_order($_SESSION['user_id'], '', '', '', 1);
    $order_item_id = $order->insert_order_item($order_id, $_POST['product-id'], $_POST['order-quantity'], calculate_order_item_price());

    echo json_encode(['order_id' => $order_id]);
}

function calculate_order_item_price()
{
    return number_format(($_POST['product-id'] * $_POST['order-quantity']), 2, '.', '');
}

function send_error_response($code, $message)
{
    http_response_code($code);
    echo json_encode(array('error' => $message));
    exit();
}
?>