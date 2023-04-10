<?php
$title = "Gifts";
$styles = "";
$page_title = "Gifts";

include_once('../includes/header.inc.php');
include_once('../includes/navbar.inc.php');
include_once('../includes/page-wrapper-start.inc.php');

require_once "../classes/product.class.php";

// Initialize a new Product instance
$product = new Product($conn);

// Get all products
$products = $product->select_all();

function renderProductCard($row) {
    $btnText = '';
    if (!isset($_SESSION['role']) || $_SESSION["role"] != 'seller') {
        $btnText = $row["quantity"] != 0 ? "Add to cart" : "Sold Out";
        $btnDisabled = $row["quantity"] == 0 ? "disabled" : "";
    } else {
        $btnText = "Edit";
        $btnDisabled = "";
    }
    
    return <<<HTML
        <div class="col-3">
            <div class="card hvr-grow">
                <a href="./gift-detail.php?id={$row['id']}" class="d-block">
                <img src="../assets/images/dummy_product_icon.png" class="card-img-top">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <div>{$row['name']}</div>
                            <div>{$row['price']}</div>
                            <div class="text-muted">{$row['description']}</div>
                        </div>
                    </div>
                </div>
                </a>
                <div class="card-footer">
                    <button class="btn btn-outline-light" {$btnDisabled}>{$btnText}</button>
                </div>
            </div>
        </div>
    HTML;
}

?>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <?php
            while ($row = $products->fetch_assoc()) {
                echo renderProductCard($row);
            }
            ?>
        </div>
    </div>
</div>

<?php
include_once('../includes/page-wrapper-end.inc.php');
$scripts = "";
include_once('../includes/footer.inc.php');
?>
