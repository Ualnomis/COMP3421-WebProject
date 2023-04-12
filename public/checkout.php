<?php
$title = "Checkout";
$styles = <<<HTML
HTML;
$page_title = "Checkout";
include_once('../includes/header.inc.php');
include_once('../includes/navbar.inc.php');
require_once('../classes/cart.class.php');
require_once('../classes/order.class.php');
include_once('../includes/page-wrapper-start.inc.php');

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $order = new Order($conn);
    $order_result = $order->get_order_by_id($order_id);
    if (!$order_result) {
        echo '<script>window.location.replace("product.php");</script>';
        exit();
    }
    if (!($order_result['status_id'] === 1)) {
        echo '<script>window.location.replace("product.php");</script>';
        exit();
    }
    $order_item_results = $order->get_order_item_by_order_id($order_id);
    $order_items = $order_item_results['order_items'];
    $total_sum_price = $order_item_results['total_sum_price'];
} else {
    echo '<script>window.location.replace("product.php");</script>';
    exit();
}


?>


<div class="page-body">
    <div class="container-xl">
        <div class="row">

            <div class="col-8">
                <form method="POST" action="../includes/pay.php" id="checkout-form">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="card-title">Personal Information</h1>
                            <div class="row row-cards">
                                <div class="col-sm-6 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">First Name</label>
                                        <input type="text" class="form-control" placeholder="First Name"
                                            name="buyer-first-name" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" class="form-control" placeholder="Last Name"
                                            name="buyer-last-name" value="" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" placeholder="Phone Number"
                                            name="buyer-phone" value="" required>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" class="form-control" placeholder="Home Address"
                                        name="buyer-home-address" value="" required>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">City</label>
                                        <input type="text" class="form-control" placeholder="City" name="buyer-city"
                                            value="" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Region</label>
                                        <select class="form-control form-select" name="buyer-region">
                                            <option value="Hong Kong Island">Hong Kong Island</option>
                                            <option value="Kowloon">Kowloon</option>
                                            <option value="New Territories">New Territories</option>
                                        </select>
                                    </div>
                                </div>

                                <h1 class="card-title">
                                    Payment Method
                                    <span class="payment payment-provider-visa mx-2"></span>
                                    <span class="payment payment-provider-mastercard me-2"></span>
                                </h1>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Card Number</label>
                                    <input type="text" class="form-control" placeholder="Card Number" name="cardnumber" pattern="\d{16}" maxlength="16" required>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <label class="form-label">Expiry date</label>
                                    <input type="text" class="form-control" id="expiry-date" placeholder="MM/YY" name="cardexpiry" pattern="(0[1-9]|1[0-2])\/?([0-9]{2})" maxlength="5" required>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <label class="form-label">CVV</label>
                                    <input type="text" class="form-control" placeholder="CVV" name="cardcvv" value="" maxlength="3"
                                        required>
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <input type="hidden" name="order_id" value="<?= $order_id ?>" />
                                    <button class="btn-primary btn">Pay Now</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title">Product</h1>
                        <div class="row row-cards">
                            <?php foreach ($order_items as $order_item): ?>

                                <div class="col-2">
                                    <img src="<?= $order_item['image_url']; ?>" />
                                    <span class="badge badge-pill bg-red">
                                        <?= $order_item['quantity'] ?>
                                    </span>
                                </div>
                                <div class="col-6">
                                    <?= $order_item['name']; ?>
                                </div>
                                <div class="col-4">
                                    HKD$
                                    <?= $order_item['price']; ?>
                                </div>

                            <?php endforeach; ?>
                        </div>
                        <div class="row row-cards mt-3">
                            <div class="col-8">
                                Total Price:
                            </div>
                            <div class="col-4">
                                <?= $total_sum_price; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once('../includes/page-wrapper-end.inc.php');
include_once('../includes/footer.inc.php');
?>