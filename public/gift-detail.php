<?php
$title = "Gift Detail";
$styles = "";
// Include the template file
include_once('../includes/header.inc.php');
include_once('../classes/product.class.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // display gift detail for the given id
    $product = new Product($conn);
    $product_result = $product->select_one($id);
    if ($product_result->num_rows > 0) {
        $product_data = $product_result->fetch_assoc();
    } else {
        header("Location: gift.php");
        exit();
    }
} else {
    header("Location: gift.php");
    exit();
}
$page_title = "Gift Detail";

include_once('../includes/navbar.inc.php');
include_once('../includes/page-wrapper-start.inc.php');
?>


<?php
$product = new Product($conn);
$product_result = $product->select_one($id);
?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <img src="<?php echo $product_data['image_url']; ?>" class="w-100" />
            </div>
            <div class="col-md-4 col-sm-12">
                <h1>
                    <?php echo $product_data['name']; ?>
                </h1>
                <h3>
                    <?php echo $product_data['description']; ?>
                </h3>
                HK$
                <?php echo $product_data['price']; ?>
                <div class="mt-3">
                    <label class="form-label">Quantity</label>
                    <div class="input-group w-50">
                        <button class="btn btn-outline-light" <?php if ($product_data['quantity'] == 0) {
                            echo "disabled";
                        } ?>>
                            -
                        </button>
                        <input type="number" class="form-control" name="order-quantity" value="1" min="1"
                            max="<?php echo $product_data['quantity']; ?>" step="1" <?php if ($product_data['quantity']  == 0) {
                                   echo "disabled";
                               } ?>>
                        <button class="btn btn-outline-light" <?php if ($product_data['quantity'] == 0) {
                            echo "disabled";
                        } ?>>
                            +
                        </button>
                    </div>
                </div>
                <?php
                if ($product_data['quantity'] != 0) {
                    echo <<<HTML
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
                    echo <<<HTML
                    <div class="row mt-3">
                        <button class="btn btn-outline-light" disabled>
                            Sold Out
                        </button>
                    </div>
                    HTML;
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
include_once('../includes/page-wrapper-end.inc.php');
$scripts = "";
include_once('../includes/footer.inc.php');
?>