<?php
$title = "Gifts";
$styles = <<<HTML
<link href="../assets/css/product.css" rel="stylesheet">
HTML;
$page_title = "";

include_once('../includes/header.inc.php');
include_once('../includes/navbar.inc.php');
require_once("../classes/product.class.php");
// Initialize a new Product instance
$product = new Product($conn);
$search_result = "";
if (isset($_GET['search'])) {
    // Get all products
    $search_result = $_GET['search'];
    $products = $product->select_by_name_or_description($search_result);
} else {
    // Get all products
    $products = $product->select_all();
}
$search_field = <<<HTML
  <form action="" method="GET">
<div class="input-group">
  
    <div class="input-icon flex-fill">
        <span class="input-icon-addon">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
        </span>
        <input type="text" value="{$search_result}" name="search" class="form-control" placeholder="Search Gift">
    </div>
    <input class="btn btn-outline-dark" type="submit" value="Search!">

</div>
</form>
HTML;

include_once('../includes/page-wrapper-start.inc.php');

function renderProductCard($row)
{
    $sold_out_badge = $row["quantity"] == 0 ? '<span class="badge bg-red">Sold Out</span>' : "";
    if (isset($_SESSION['role']) && $_SESSION["role"] === 'seller') {
        $cart_footer = <<<HTML
            <div class="card-footer d-flex justify-content-end">
        
            <a class="btn btn-outline-dark" href="./edit-product.php?id={$row['id']}">Edit</a>
            <a class="btn btn-danger ms-3" href="./delete-product.php?id={$row['id']}">Delete</a>
            </div>
        HTML;
    } else {
        $cart_footer = <<<HTML
        HTML;
    }

    if ($row["status"] === 'hide' && (!isset($_SESSION["role"]) || !($_SESSION["role"] === "seller"))) {
        return "";
    }

    return <<<HTML
        <div class="col-md-4 col-lg-3 col-sm-6">
            <div class="card hvr-grow w-100 h-100">
                <a href="./product-detail.php?id={$row['id']}" class="d-block hover:no-underline">
                <img src="{$row['image_url']}" class="card-img-top img-fiuld h-[250px]" style="object-fit: contain; object-position: center;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <div>{$row['name']} {$sold_out_badge}</div>
                            <div>{$row['price']}</div>
                            <div>{$row['quantity']} left</div>
                        </div>
                    </div>
                </div>
                </a>
                {$cart_footer}
            </div>
        </div>
    HTML;
}


?>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <?php
        if (isset($_GET['deleted'])) {
            if ($_GET['deleted'] === 'true') {
                echo '<div class="alert alert-success">Product deleted successfully.</div>';
            }
        }
        ?>
        <div class="row row-cards">
            <?php
            if (isset($_SESSION['role']) && $_SESSION["role"] === 'seller') {
                echo <<<HTML
                <div class="col-md-4 col-lg-3 col-sm-6">
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
$modals = <<<HTML
<div></div>
HTML;
include_once('../includes/footer.inc.php');
?>