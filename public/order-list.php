<?php
$title = "Order List";
$styles = <<<HTML
<style>
    .order-row {
        background-color: #2563eb;
        color: white;
    }
</style>
HTML;
$page_title = "Order List";

include_once('../includes/header.inc.php');
include_once('../includes/navbar.inc.php');
require_once('../classes/order.class.php');

// Get the orders for the current user
$user_id = $_SESSION['user_id']; // Assumes user is logged in
$order = new Order($conn);
$orders = $order->get_order_by_id($user_id);
?>

<div class="container-xl mt-3">
    <div class="row">
        <div class="col-12">
            <h1>Order List</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($orders)) : ?>
                        <?php foreach ($orders as $order) : ?>
                            <tr class="order-row">
                                <td><?= $order['id'] ?></td>
                                <td><?= $order['created_at'] ?></td>
                                <td>$<?= number_format($order['total_price'], 2) ?></td>
                                <td><?= $order['status'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4">No orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include_once('../includes/page-wrapper-end.inc.php');
$scripts = "";
include_once('../includes/footer.inc.php');
?>