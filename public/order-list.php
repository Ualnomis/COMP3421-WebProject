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
$user_role = $_SESSION['role'];
$order = new Order($conn);
if($user_role == "buyer"){
    $orders = $order->get_orders_by_user_id($user_id);
} else {
    $orders = $order->get_all_orders();
}
print_r($orders)
?>

<div class="container-xl mt-3">
    <div class="row">
        <div class="col-12">
            <h1>Order List</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Buyer ID</th>
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
                                <td><?= $order['buyer_id'] ?></td>
                                <td><?= $order['order_date'] ?></td>
                                <td>$<?= number_format($order['total'], 2) ?></td>
                                <td><a href="#" ><i class="fa-solid fa-magnifying-glass"></i></a></td>
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