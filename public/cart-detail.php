<?php
$title = "Cart Detail";
$styles = <<<HTML
<link href="../assets/css/cart-detail.css" rel="stylesheet">
HTML;
$page_title = "Shopping Cart";


include_once('../includes/header.inc.php');
include_once('../includes/navbar.inc.php');
include_once('../includes/page-wrapper-start.inc.php');
include_once('../classes/cart.class.php');

if (isset($_SESSION['role']) && $_SESSION['role'] != 'buyer') {
    echo '<script>window.location.replace("product.php");</script>';
    exit();
}
// Create a new instance of the Cart class with a database connection
$cart = new Cart($conn);

// Get the cart items for the current user
$user_id = $_SESSION['user_id']; // Assumes user is logged in
$cart_id = $cart->select($user_id)['id'];
$cart_items = $cart->getCartItems($cart_id)['cart_items'];

?>

<!-- Page body -->
<div class="page-body" id="cart_detail">
    <div class="container-xl">
        <!-- Content here -->
        <div class="card">
            <div class="card-body">
                <h1 class="card-title"></h1>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-vcenter">
                                <thead>
                                    <tr>
                                        <th colspan="2">Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th class="w-1"></th>
                                    </tr>
                                </thead>
                                <tbody id="cart-body">
                                </tbody>
                                <tfoot id="cart-foot">
                                </tfoot>
                            </table>
                            <div id="cart-checkout">
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
$scripts = <<<HTML
<!-- <script src="../assets/js/cart-detail.js"></script> -->
HTML;
$modals = <<<HTML
HTML;
include_once('../includes/footer.inc.php');
?>