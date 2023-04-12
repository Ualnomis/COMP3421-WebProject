<?php ob_start(); ?>
<?php
$title = "Add Gift";
$styles = <<<HTML
HTML;
$page_title = "Add Gift";


include_once('../includes/header.inc.php');
include_once('../includes/navbar.inc.php');
include_once('../includes/page-wrapper-start.inc.php');
require_once('../classes/product.class.php');

$product = new Product($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seller_id = $_SESSION['user_id'];
    $name = $_POST['product-name'];
    $price = $_POST['product-price'];
    $quantity = $_POST['product-quantity'];
    $description = $_POST['product-description'];
    $status = $_POST['product-status'];
    $imageUrl = "../assets/images/dummy_product_icon.png"; // set default image URL

    if (!empty($_FILES["product-img"]["tmp_name"])) {
        // Handle file upload if a file was uploaded
        $targetDir = "../assets/images/";
        $fileName = uniqid() . '_' . basename($_FILES["product-img"]["name"]);
        $targetFile = $targetDir . $fileName;
        move_uploaded_file($_FILES["product-img"]["tmp_name"], $targetFile);
        $imageUrl = "../assets/images/" . $fileName;
    }

    // Insert into database
    if ($product->insert($seller_id, $name, $description, $price, $quantity, $imageUrl, $status)) {
        // Return success response
        header('Location: product.php');
        ob_end_flush();
        exit();
    } else {
        // Return error response
        echo "Error adding product";
    }
}
?>

<!-- Page body -->
<div class="page-body" id="add_product">
    <div class="container-xl">
        <!-- Content here -->
        <form method="post" enctype="multipart/form-data">
            <div class="row row-cards">
                <div class="col-4 flex flex-wrap justify-center items-center">
                    <img id="preview" src="../assets/images/dummy_product_icon.png"
                        class="img-fiuld w-[200px] h-auto flex-1" />
                    <div class="d-flex justify-content-center mt-3 flex-1">
                        <div class="btn btn-outline-light btn-rounded">
                            <label class="form-label m-1" for="product-img">Choose file</label>
                            <input type="file" class="form-control d-none" name="product-img" id="product-img"
                                onchange="previewImage(event)" accept="image/*" multiple="false" />
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" name="product-name" placeholder="Input placeholder">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product Price</label>
                        <input type="number" class="form-control" name="product-price" placeholder="Input placeholder"
                            min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product Quantity</label>
                        <input type="number" class="form-control" name="product-quantity"
                            placeholder="Input placeholder" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product Description</label>
                        <textarea class="form-control" data-bs-toggle="autosize" name="product-description"
                            placeholder="Description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Display</label>
                        <select class="form-control form-select" name="product-status">
                            <option value="show">Show</option>
                            <option value="hide">Hide</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <input type="submit" class="btn btn-outline-light" value="Add" />
            </div>
        </form>
    </div>
</div>

<?php
include_once('../includes/page-wrapper-end.inc.php');
$scripts = <<<HTML
<script src="../assets/js/add-product.js"></script>
HTML;
include_once('../includes/footer.inc.php');
?>