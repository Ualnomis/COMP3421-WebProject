<?php
$title = "Order Items";
$styles = <<<HTML
HTML;
$page_title = "Order Items";

include_once('../includes/header.inc.php');
include_once('../includes/navbar.inc.php');
require_once('../classes/order.class.php');
$order = new Order($conn);
$order_id = $_GET['order_id'];
$order_items_data = $order->get_order_item_by_order_id($order_id);

?>

<div class="container-xl mt-3">
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
                    <?php if ($order_items_data['order_items']->num_rows > 0): ?>
                        <?php while ($row = $order_items_data['order_items']->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?= $row['product_id'] ?>
                                </td>
                                <td>
                                    <?= $row['name'] ?>
                                </td>
                                <td><img src="<?= $row['image_url'] ?>" width="50" height="50"></td>
                                <td>
                                    <?= $row['quantity'] ?>
                                </td>
                                <td>$
                                    <?= $row['product_price'] ?>
                                </td>
                                <td>$
                                    <?= $row['price'] ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No order items found.</td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td colspan="5">Total:</td>
                        <td>$
                            <?= $order_items_data['total_sum_price'] ?>
                        </td>
                    </tr>
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