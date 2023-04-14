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

if (!isset($_SESSION['user_id'])) {
    echo '<script>window.location.replace("../public/");</script>';
    exit;
} else if ($_SESSION['role'] === 'seller') {

} else if ($_SESSION['role'] === 'buyer') {
    echo '<script>window.location.replace("../public/");</script>';
    exit;
}

$product = new Product($conn);

$error = false;
$errorMessage = '';
$name="";
$price="";
$quantity="";
$description = "";
$status = "";
$imageUrl = "../assets/images/dummy_product_icon.png";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seller_id = $_SESSION['user_id'];
    $name = $_POST['product-name'];
    $price = $_POST['product-price'];
    $quantity = $_POST['product-quantity'];
    $description = $_POST['product-description'];
    $status = $_POST['product-status'];
    $imageUrl = "../assets/images/dummy_product_icon.png"; // set default image URL

    if (empty($description)) {
        $error = true;
        $errorMessage = 'Please enter a product description.';
    }

    if (!is_numeric($quantity) || $quantity < 0) {
        $error = true;
        $errorMessage = 'Please enter a valid product quantity.';
    } else {
        $quantity = (int) $quantity;
    }

    if (!is_numeric($price) || $price < 0) {
        $error = true;
        $errorMessage = 'Please enter a valid product price.';
    } else {
        $price = number_format((float) $price, 2, '.', '');
    }

    if (empty($name)) {
        $error = true;
        $errorMessage = 'Please enter a product name.';
    }
    if (strlen($name) > 255) {
        $name = substr($name, 0, 255);
    }

    if (!$error) {
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
            $error = true;
            $errorMessage = 'Error adding product.';
        }
    }
}
?>

<!-- Page body -->
<div class="page-body" id="add_product">
    <div class="container-xl">
        <!-- Content here -->
        <form method="post" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title"></h1>
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <?php echo $errorMessage; ?>
                        </div>
                    <?php endif; ?>
                    <div class="row row-cards">
                        <div class="col-4 flex flex-wrap justify-center items-center">
                            <img id="preview" src="../assets/images/dummy_product_icon.png"
                                class="img-fiuld w-[200px] h-auto flex-1" />
                            <div class="d-flex justify-content-center mt-3 flex-1">
                                <div class="btn btn-outline-dark btn-rounded">
                                    <label class="form-label m-1" for="product-img">Choose file</label>
                                    <input type="file" class="form-control d-none" name="product-img" id="product-img"
                                        accept="image/*" multiple="false" value="<?= $name; ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="mb-3">
                                <label class="form-label required">Product Name</label>
                                <input type="text" class="form-control" name="product-name"
                                    placeholder="Product Name" value="<?= $name; ?>" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Product Price</label>
                                <input type="text" class="form-control" name="product-price"
                                    placeholder="Product Price" value="<?= $price; ?>" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Product Quantity</label>
                                <input type="number" class="form-control" name="product-quantity"
                                    placeholder="Product Quantity" value="<?= $quantity; ?>" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Product Description</label>
                                <textarea class="form-control" data-bs-toggle="autosize" name="product-description"
                                    placeholder="Product Description" style="border: black 1px solid;" value="<?= $description; ?>"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Display</label>
                                <select class="form-control form-select" name="product-status" value="<?= $status; ?>">
                                    <option value="show">Show</option>
                                    <option value="hide">Hide</option>
                                </select>
                            </div>
                            <div class="d-flex justify-content-end">
                                <input type="submit" class="btn btn-outline-green " value="Add" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
include_once('../includes/page-wrapper-end.inc.php');
$scripts = <<<HTML
<script src="../assets/js/add-product.js"></script>
HTML;
$modals = <<<HTML
HTML;
include_once('../includes/footer.inc.php');
?>