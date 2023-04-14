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

if (!isset($_SESSION['user_id'])) {
    echo '<script>window.location.replace("../public/");</script>';
    exit;
} else if ($_SESSION['role'] === 'seller') {
    echo '<script>window.location.replace("../public/");</script>';
    exit;
} else if ($_SESSION['role'] === 'buyer') {

}

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
    if (!($order_result['buyer_id'] === $_SESSION['user_id'])) {
        echo '<script>window.location.replace("./");</script>';
        exit();
    }
    $order_item_results = $order->get_order_item_by_order_id($order_id);
    $order_items = $order_item_results['order_items'];
    $total_sum_price = $order_item_results['total_sum_price'];
} else {
    echo '<script>window.location.replace("product.php");</script>';
    exit();
}

$error = isset($_SESSION['error']) ? $_SESSION['error'] : false;
$errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

?>


<div class="page-body">
    <div class="container-xl">
        <div class="row">

            <div class="col-8">
                <form method="POST" action="../includes/pay.inc.php" id="checkout-form">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="card-title">Personal Information</h1>
                            <?php if ($error): ?>
                                <div class="alert alert-danger">
                                    <?php echo $errorMessage; ?>
                                </div>
                                <?php
                                // Reset error and error message for the next request
                                $_SESSION['error'] = false;
                                $_SESSION['error_message'] = '';
                                ?>
                            <?php endif; ?>
                            <div class="row row-cards">
                                <!-- First Name -->
                                <div class="col-sm-6 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">First Name</label>
                                        <input type="text" class="form-control" placeholder="First Name"
                                            name="buyer-first-name"
                                            value="<?php echo isset($_SESSION['form_data']['buyer-first-name']) ? htmlspecialchars($_SESSION['form_data']['buyer-first-name']) : ''; ?>"
                                            required>
                                    </div>
                                </div>

                                <!-- Last Name -->
                                <div class="col-sm-6 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Last Name</label>
                                        <input type="text" class="form-control" placeholder="Last Name"
                                            name="buyer-last-name"
                                            value="<?php echo isset($_SESSION['form_data']['buyer-last-name']) ? htmlspecialchars($_SESSION['form_data']['buyer-last-name']) : ''; ?>"
                                            required>
                                    </div>
                                </div>

                                <!-- Phone Number -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label required">Phone Number</label>
                                        <input type="text" class="form-control" placeholder="Phone Number"
                                            name="buyer-phone"
                                            value="<?php echo isset($_SESSION['form_data']['buyer-phone']) ? htmlspecialchars($_SESSION['form_data']['buyer-phone']) : ''; ?>"
                                            required
                                            maxlength="8"
                                            >
                                    </div>
                                </div>

                                <!-- Home Address -->
                                <div class="col-12 mb-3">
                                    <label class="form-label required">Address</label>
                                    <input type="text" class="form-control" placeholder="Home Address"
                                        name="buyer-home-address"
                                        value="<?php echo isset($_SESSION['form_data']['buyer-home-address']) ? htmlspecialchars($_SESSION['form_data']['buyer-home-address']) : ''; ?>"
                                        required>
                                </div>

                                <!-- City -->
                                <div class="col-sm-6 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">City</label>
                                        <input type="text" class="form-control" placeholder="City" name="buyer-city"
                                            value="<?php echo isset($_SESSION['form_data']['buyer-city']) ? htmlspecialchars($_SESSION['form_data']['buyer-city']) : ''; ?>"
                                            required>
                                    </div>
                                </div>

                                <!-- Region -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Region</label>
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
                                <!-- Card Number -->
                                <div class="col-12 mb-3">
                                    <label class="form-label required">Card Number</label>
                                    <input type="text" class="form-control" placeholder="Card Number" name="cardnumber"
                                        value="<?php echo isset($_SESSION['form_data']['cardnumber']) ? htmlspecialchars($_SESSION['form_data']['cardnumber']) : ''; ?>"
                                        maxlength="16" required>
                                </div>

                                <!-- Expiry date -->
                                <div class="col-sm-6 col-md-3">
                                    <label class="form-label required">Expiry date</label>
                                    <input type="text" class="form-control" id="expiry-date" placeholder="MM/YY"
                                        name="cardexpiry"
                                        value="<?php echo isset($_SESSION['form_data']['cardexpiry']) ? htmlspecialchars($_SESSION['form_data']['cardexpiry']) : ''; ?>"
                                        maxlength="5" required>
                                </div>

                                <!-- CVV -->
                                <div class="col-sm-6 col-md-3">
                                    <label class="form-label required">CVV</label>
                                    <input type="text" class="form-control" placeholder="CVV" name="cardcvv"
                                        value="<?php echo isset($_SESSION['form_data']['cardcvv']) ? htmlspecialchars($_SESSION['form_data']['cardcvv']) : ''; ?>"
                                        maxlength="3" required>
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
// Unset form_data session variable after displaying the form values
if (isset($_SESSION['form_data'])) {
    unset($_SESSION['form_data']);
}
?>
<?php
include_once('../includes/page-wrapper-end.inc.php');
$modals = <<<HTML
HTML;
include_once('../includes/footer.inc.php');
?>