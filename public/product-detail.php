<?php
$title = "Gift Detail";
$styles = "";

include_once('../includes/header.inc.php');
include_once('../classes/product.class.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $product = new Product($conn);
    $product_result = $product->select_one($id);
    if ($product_result->num_rows > 0) {
        $product_data = $product_result->fetch_assoc();
    } else {
        header("Location: product.php");
        exit();
    }
} else {
    header("Location: product.php");
    exit();
}

$page_title = "Gift Detail";

include_once('../includes/navbar.inc.php');
include_once('../includes/page-wrapper-start.inc.php');

function renderActionButton($product_data) {
    if ($product_data['quantity'] != 0) {
        return <<<HTML
            <div class="row mt-3">
                <button class="btn btn-outline-light">
                    Add to Cart
                </button>
            </div>
            <div class="row mt-3">
                <button class="btn btn-outline-light">
                    Buy
                </button>
            </div>
        HTML;
    } else {
        return <<<HTML
        <div class="row mt-3">
            <button class="btn btn-outline-light" disabled>
                Sold Out
            </button>
        </div>
        HTML;
    }
}
?>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <img src="<?= $product_data['image_url']; ?>" class="w-100" />
            </div>
            <div class="col-md-4 col-sm-12">
                <h1><?= $product_data['name']; ?></h1>
                <h3><?= $product_data['description']; ?></h3>
                HK$<?= $product_data['price']; ?>
                <div class="mt-3">
                    <label class="form-label">Quantity</label>
                    <div class="input-group w-50">
                        <button class="btn btn-outline-light" <?= $product_data['quantity'] == 0 ? "disabled" : ""; ?>>
                            -
                        </button>
                        <input type="number" class="form-control" name="order-quantity" value="1" min="1"
                            max="<?= $product_data['quantity']; ?>" step="1" <?= $product_data['quantity'] == 0 ? "disabled" : ""; ?>>
                        <button class="btn btn-outline-light" <?= $product_data['quantity'] == 0 ? "disabled" : ""; ?>>
                            +
                        </button>
                    </div>
                </div>
                <?= renderActionButton($product_data); ?>
            </div>
        </div>
    </div>
</div>

<?php
include_once('../includes/page-wrapper-end.inc.php');
$scripts = "";
include_once('../includes/footer.inc.php');
?>
