<?php
$title = "Gifts";
$styles = <<<HTML
<link href="../assets/css/product.css" rel="stylesheet">
HTML;
$page_title = "Gifts";

include_once('../includes/header.inc.php');
include_once('../includes/navbar.inc.php');
include_once('../includes/page-wrapper-start.inc.php');

require_once "../classes/product.class.php";

// Initialize a new Product instance
$product = new Product($conn);

// Get all products
$products = $product->select_all();

function renderProductCard($row)
{
    $btnText = '';
    $href = "";
    if (!isset($_SESSION['role']) || $_SESSION["role"] != 'seller') {
        $btnText = $row["quantity"] != 0 ? "Add to cart" : "Sold Out";
        $btnDisabled = $row["quantity"] == 0 ? "disabled" : "";
    } else {
        $btnText = <<<HTML
            <a href="./edit-product.php?id={$row['id']}">Edit</a>
        HTML;
        $btnDisabled = "";
    }

    return <<<HTML
        <div class="col-3">
            <div class="card hvr-grow w-100 h-100">
                <a href="./product-detail.php?id={$row['id']}" class="d-block">
                <img src="{$row['image_url']}" class="card-img-top img-fiuld">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <div>{$row['name']}</div>
                            <div>{$row['price']}</div>
                            <div>{$row['quantity']} left</div>
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
            if (isset($_SESSION['role']) && $_SESSION["role"] == 'seller') {
                echo <<<HTML
                <div class="col-3">
                    <a href="./add-product.php" class="">
                    <div class="card hvr-grow w-100 h-100 d-flex align-items-center justify-content-center text-center">
                        <div class="card-body d-flex align-items-center justify-content-center flex-column">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus d-block" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 5l0 14"></path>
                                <path d="M5 12l14 0"></path>
                            </svg>
                            <div class="fs-1">Add Product</div>
                        </div>
                    </div>
                    </a>
                </div>
                HTML;
            }
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