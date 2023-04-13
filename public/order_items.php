<?php
require_once '../config/db_connection.php';
require_once('../classes/order.class.php');
$order = new Order($conn);
$order_id = $_GET['order_id'];
$order_items_data = $order->get_order_item_by_order_id($order_id);
$order_data = $order->get_order_by_id($order_id);
if (!isset($_SESSION)) {
    echo "Please Login First";
    exit();
} else if ($_SESSION['role'] === 'buyer' && $order_data['buyer_id'] != $_SESSION['user_id']) {
    echo "Invalid List Order Request.";
    exit();
}

$status = "";

if ($order_data['status_id'] === 1) {
    $status = '<span class="status status-red">' . $order_data['status_name'] . '</span>';
} else if ($order_data['status_id'] === 2) {
    $status = '<span class="status status-green">' . $order_data['status_name'] . '</span>';
} else if ($order_data['status_id'] === 3) {
    $status = '<span class="status status-blue">' . $order_data['status_name'] . '</span>';
} else if ($order_data['status_id'] === 4) {
    $status = '<span class="status status-grey">' . $order_data['status_name'] . '</span>';
}

$html = <<<HTML
<div class="row">
    <div class="row mt-1">
        <div class="col">
            Buyer Name:
        </div>
        <div class="col">
            {$order_data['buyer_name']}
        </div>
    </div>
    <div class="row mt-1">
        <div class="col">
            Buyer Phone:
        </div>
        <div class="col">
        {$order_data['buyer_phone']}
        </div>
    </div>
    <div class="row mt-1">
        <div class="col">
            Buyer Address:
        </div>
        <div class="col">
        {$order_data['buyer_address']}
        </div>
    </div>
    <div class="row mt-1">
        <div class="col">
            Order date:
        </div>
        <div class="col">
        {$order_data['order_date']}
        </div>
    </div>
    <div class="row mt-1">
        <div class="col">
            Status:
        </div>
        <div class="col">
            {$status}
        </div>
    </div>
    </div>
HTML;
$html .= <<<HTML
    <div class="row">
        <div class="col-12">
            <h1>Order Items</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
HTML;

if ($order_items_data['order_items']->num_rows > 0) {
    while ($row = $order_items_data['order_items']->fetch_assoc()) {
        $html .= <<<HTML
                            <tr>
                                <td>
                                    {$row['product_id']}
                                </td>
                                <td>
                                    {$row['name']}
                                </td>
                                <td><img src="{$row['image_url']}" width="50" height="50"></td>
                                <td>
                                    {$row['quantity']}
                                </td>
                                <td>$
                                    {$row['product_price']}
                                </td>
                                <td>$
                                    {$row['price']}
                                </td>
                            </tr>
HTML;
    }
} else {
    $html .= <<<HTML
                        <tr>
                            <td colspan="6">No order items found.</td>
                        </tr>
HTML;
}

$html .= <<<HTML
                    <tr>
                        <td colspan="5">Total:</td>
                        <td>$
                            {$order_items_data['total_sum_price']}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
HTML;

echo $html;
?>