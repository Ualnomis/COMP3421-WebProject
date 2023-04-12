<?php
require_once '../config/db_connection.php';
require_once '../classes/cart.class.php';
require_once '../classes/order.class.php';

function isPostDataValid($post_data)
{
    $required_fields = [
        'order_id',
        'buyer-home-address',
        'buyer-city',
        'buyer-region',
        'buyer-first-name',
        'buyer-last-name',
        'buyer-phone'
    ];

    foreach ($required_fields as $field) {
        if (!isset($post_data[$field])) {
            return false;
        }
    }

    return true;
}

function processOrder($post_data, $conn)
{
    $order = new Order($conn);
    $buyer_name = "{$post_data['buyer-first-name']} {$post_data['buyer-last-name']}";
    $buyer_address = "{$post_data['buyer-home-address']}, {$post_data['buyer-city']}, {$post_data['buyer-region']}";

    $order->update_order($post_data['order_id'], $buyer_name, $post_data['buyer-phone'], $buyer_address, 2);
}

if (isPostDataValid($_POST)) {
    processOrder($_POST, $conn);
    echo '<script>window.location.replace("../public/order-list.php");</script>';
} else {
    echo '<script>window.location.replace("../public/checkout.php?order_id=' . $order_id . '&success=fail");</script>';
}
?>