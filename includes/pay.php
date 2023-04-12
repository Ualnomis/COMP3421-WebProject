<?php
require_once '../config/db_connection.php';
require_once '../classes/cart.class.php';
require_once '../classes/order.class.php';
require_once('../classes/product.class.php');
$error_msg = "";
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
    global $error_msg;
    $order = new Order($conn);
    $buyer_name = "{$post_data['buyer-first-name']} {$post_data['buyer-last-name']}";
    $buyer_address = "{$post_data['buyer-home-address']}, {$post_data['buyer-city']}, {$post_data['buyer-region']}";

    // Start transaction
    $conn->begin_transaction();

    // Check product quantity
    $order_items = $order->get_order_item_by_order_id($post_data['order_id'])['order_items'];
    $not_enough_quantity = false;
    foreach ($order_items as $order_item) {
        if (!$order->check_product_quantity($order_item['product_id'], $order_item['quantity'])) {
            $not_enough_quantity = true;
            break;
        }
        if ($order_item['status'] != 'show') {
            $conn->rollback(); // Rollback transaction
            $order->update_order($post_data['order_id'], $buyer_name, $post_data['buyer-phone'], $buyer_address, 4);
            $error_msg = "There are removed products.";
            return false;
        }
    }

    if ($not_enough_quantity) {
        $conn->rollback(); // Rollback transaction
        $order->update_order($post_data['order_id'], $buyer_name, $post_data['buyer-phone'], $buyer_address, 4);
        $error_msg = "Not enough Quantity";
        return false;
    } else {
        $product = new Product($conn);
        foreach ($order_items as $order_item) {
            $product->decrease_product_quantity($order_item['product_id'], $order_item['quantity']);
        }
        $order->update_order($post_data['order_id'], $buyer_name, $post_data['buyer-phone'], $buyer_address, 2);
        $conn->commit(); // Commit transaction
        return true;
    }
}

if (isPostDataValid($_POST)) {
    if (processOrder($_POST, $conn)) {
        echo '<script>window.location.replace("../public/order-list.php");</script>';
    } else {
        echo <<<HTML
        <script>
            alert("Order Cancelled: {$error_msg}");
            window.location.replace("../public/product.php");
        </script>
        HTML;
    }

} else {
    echo '<script>window.location.replace("../public/checkout.php?order_id=' . $order_id . '&success=fail");</script>';
}
?>