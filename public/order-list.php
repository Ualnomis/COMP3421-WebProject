<?php
$title = "Order List";
$styles = <<<HTML

HTML;
$page_title = "Order List";

include_once('../includes/header.inc.php');
include_once('../includes/navbar.inc.php');
require_once('../classes/order.class.php');

// Get the orders for the current user
$user_id = $_SESSION['user_id']; // Assumes user is logged in
$user_role = $_SESSION['role'];
$order = new Order($conn);
if ($user_role == "buyer") {
    $orders = $order->get_orders_by_user_id($user_id);
} else {
    $orders = $order->get_all_orders();
}
$perPage = 10;
$totalOrders = count($orders);
$totalPages = ceil($totalOrders / $perPage);
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $perPage;
$orders = array_slice($orders, $offset, $perPage);

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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                            <tr class="order-row">
                                <td>
                                    <?= $order['id'] ?>
                                </td>
                                <td>
                                    <?= $order['buyer_id'] ?>
                                </td>
                                <td>
                                    <?= $order['order_date'] ?>
                                </td>
                                <td>$
                                    <?= $order['total'] ?>
                                </td>
                                <td>
                                    <?= $order['status_name'] ?>
                                </td>
                                <td>
                                    <?php if ($order['status_id'] === 1 && $_SESSION['role'] == 'buyer'): ?>
                                        <a href="./checkout.php?order_id=<?= $order['id'] ?>" class="btn btn-primary">Checkout Now</a>
                                    <?php else: ?>
                                        <a href="./order_items.php?order_id=<?= $order['id'] ?>"><i class="fa-solid fa-magnifying-glass"></i></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="flex justify-center mt-5">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li>
                            <a href="?page=<?= $i ?>" class="<?php if ($page === $i)
                                  echo 'bg-blue-500 text-white'; ?> hover:bg-blue-400 px-3 py-2 rounded"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </div>

        </div>
    </div>
</div>

<?php
include_once('../includes/page-wrapper-end.inc.php');
$scripts = "";
include_once('../includes/footer.inc.php');
?>