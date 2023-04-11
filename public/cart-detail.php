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
// Create a new instance of the Cart class with a database connection
$cart = new Cart($conn);

// Get the cart items for the current user
$user_id = $_SESSION['user_id']; // Assumes user is logged in
$cart_data = $cart->select($user_id);
$cart_id = $cart_data['id'];
$cart_items = $cart->getCartItems($cart_id);
$total_price = 0;
foreach ($cart_items as &$cart_item) {
    $total_price += $cart_item['price'];
    if ($cart_item['quantity'] > $cart_item['remain_quantity']) {
        $cart_item['quantity'] = $cart_item['remain_quantity'];
    }
}


?>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <!-- Content here -->
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
                        <tbody>
                            <?php foreach ($cart_items as $cart_item): ?>
                                <tr>
                                    <td>
                                        <img class="cart-item-img" src="<?php echo $cart_item['image_url']; ?>" />
                                    </td>
                                    <td>
                                        <?= $cart_item['name']; ?>
                                    </td>
                                    <td>
                                        <form method="post">
                                            <div class="input-group w-50">
                                                <button class="btn btn-outline-light btn-minus-quantity">
                                                    -
                                                </button>
                                                <input type="number" class="form-control" name="order-quantity"
                                                    value="<?= $cart_item['quantity']; ?>"
                                                    min="1" max="<?= $cart_item['remain_quantity']; ?>" step="1" pattern="[0-9]*">
                                                <button class="btn btn-outline-light btn-add-quantity">
                                                    +
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                    <?= $cart_item['price']; ?>
                                    </td>
                                    <td>
                                        <form action="delete-cart-item.php" method="post">
                                            <input type="hidden" name="cart_item_id"
                                                value="<?= $cart_item['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="3"></td>
                                <th>Total Price:</th>
                                <td>
                                    <?php echo $total_price; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once('../includes/page-wrapper-end.inc.php');
$scripts = <<<HTML
<script src="../assets/js/cart-detail.js"></script>
HTML;
include_once('../includes/footer.inc.php');
?>