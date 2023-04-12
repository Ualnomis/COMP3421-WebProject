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
$cart_id = $cart->select($user_id)['id'];
$cart_items = $cart->getCartItems($cart_id)['cart_items'];
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
                                        <img class="cart-item-img w-[200px] h-auto" src="<?php echo $cart_item['image_url']; ?>" />
                                    </td>
                                    <td>
                                        <?= $cart_item['name']; ?>
                                    </td>
                                    <form method="post">
                                        <input type="hidden" class="cart-item-id" value="<?= $cart_item['id']; ?>">
                                        <td>
                                            <div class="input-group w-50">
                                                <button class="btn btn-outline-light btn-minus-quantity">
                                                    -
                                                </button>
                                                <input type="number" class="form-control" name="order-quantity"
                                                    value="<?= $cart_item['quantity']; ?>" min="1"
                                                    max="<?= $cart_item['remain_quantity']; ?>" step="1" pattern="[0-9]*">
                                                <button class="btn btn-outline-light btn-add-quantity">
                                                    +
                                                </button>
                                            </div>

                                        </td>
                                        <td>
                                        <td class="cart-item-sum-price">
                                            <?= $cart_item['sum_price']; ?>
                                        </td>
                                        </td>
                                    </form>
                                    <td>
                                        <form action="../includes/delete-cart-item.inc.php" method="post">
                                            <input type="hidden" name="cart_item_id" value="<?= $cart_item['id']; ?>">
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Remove</button>
                                        </form>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td>
                                    <form action="../includes/clear-cart.php" method="post">
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Clear Cart</button>
                                    </form>    
                                </td>
                                <td colspan="2"></td>
                                <th>Total Price:</th>
                                <td class="total-price">
                                    <?= $cart->getCartItems($cart_id)['total_sum_price']; ?>
                                </td>
                            </tr>

                        </tfoot>
                    </table>
                    <div class="d-flex justify-content-end">
                        <a href="checkout.php" class="btn btn-outline-light mt-3">Proceed to Checkout</a>
                    </div>

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