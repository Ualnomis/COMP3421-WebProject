<?php
$title = "Order List";
$styles = <<<HTML

HTML;
$page_title = "Order List";

include_once('../includes/header.inc.php');
include_once('../includes/navbar.inc.php');
require_once('../classes/order.class.php');
if (!isset($_SESSION['role'])) {
    echo <<<HTML
    <script>window.location.replace("../public/");</script>
    HTML;
}

// Get the orders for the current user
$user_id = $_SESSION['user_id']; // Assumes user is logged in
$user_role = $_SESSION['role'];
$order = new Order($conn);
if ($_SESSION['role'] == "buyer") {
    $orders = $order->get_orders_by_user_id($user_id);
} else if ($_SESSION['role'] == "seller") {
    $orders = $order->get_all_orders();
}
$perPage = 10;
$totalOrders = count($orders);
$totalPages = ceil($totalOrders / $perPage);
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $perPage;
$orders = array_slice($orders, $offset, $perPage);
$status_option = <<<HTML
HTML;
$all_order_status = $order->get_all_order_status();
foreach ($all_order_status as $order_status) {
    $status_option .= <<<HTML
    <option value="{$order_status['id']}">{$order_status['name']}</option>
    HTML;
}

?>
<div class="page-body" id="list-order">
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (is_array($orders) && count($orders) > 0): ?>
                            <?php foreach ($orders as $order): ?>
                                <tr class="order-row">
                                    <td>
                                        <?= $order['id'] ?>
                                    </td>
                                    <td>
                                        <?= $order['order_date'] ?>
                                    </td>
                                    <td>$
                                        <?= $order['total'] ?>
                                    </td>
                                    <td>
                                        <div class="datagrid-item">
                                            <div class="datagrid-content">
                                                <?php if ($order['status_id'] === 1): ?>
                                                    <span class="status status-red">
                                                        <?= $order['status_name'] ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($order['status_id'] === 2): ?>
                                                    <span class="status status-green">
                                                        <?= $order['status_name'] ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($order['status_id'] === 3): ?>
                                                    <span class="status status-blue">
                                                        <?= $order['status_name'] ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($order['status_id'] === 4): ?>
                                                    <span class="status status-grey">
                                                        <?= $order['status_name'] ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($order['status_id'] === 1 && $_SESSION['role'] == 'buyer'): ?>
                                            <a href="./checkout.php?order_id=<?= $order['id'] ?>" class="btn btn-primary">Checkout
                                                Now</a>
                                        <?php else: ?>
                                            <button class="btn btn-primary view-order-details" data-bs-toggle="modal"
                                                data-bs-target="#orderDetailsModal" data-order-id="<?= $order['id'] ?>" data-order-status="<?= $order['status_id'] ?>">
                                                View Details
                                            </button>
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
    $scripts = <<<HTML
HTML;
    $modals = <<<HTML
    <!-- Order Details Modal -->
    <div class="modal" id="orderDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Order Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="orderDetails">
                        <!-- Order details will be populated here -->
                    </div>
HTML;

    if ($user_role == 'seller') {
        $modals .= <<<HTML
                    <div class="modal-footer w-100">
                        <form id="updateOrderStatusForm" class="w-100">
                            <input type="hidden" name="order_id" id="order_id">
                            <div class="form-group">
                                <label for="order_status">Status:</label>
                                <select class="form-select mt-1" name="order_status" id="order_status">
                                    {$status_option}
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-green mt-3">Update Status</button>
                        </form>
                    </div>
HTML;
    }

    $modals .= <<<HTML
                </div>
            
        </div>
    </div>
HTML;
    include_once('../includes/footer.inc.php');
    ?>