<?php ob_start(); ?>
<?php
$title = "Edit Gift";
$styles = <<<HTML
HTML;
$page_title = "Edit Gift";

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
$error = '';

if (isset($_GET['id']) && $_SESSION['role'] == 'seller') {
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seller_id = $_SESSION['user_id'];
    $name = $_POST['product-name'];
    $price = $_POST['product-price'];
    $quantity = $_POST['product-quantity'];
    $description = $_POST['product-description'];
    $status = $_POST['product-status'];
    $imageUrl = $product_data['image_url'];

    // Validation
    if (strlen($name) > 255) {
        $name = substr($name, 0, 255);
    }

    if (!is_numeric($price)) {
        $error = 'Price must be a number.';
    } else {
        $price = number_format((float) $price, 2, '.', '');
    }

    if (!is_numeric($quantity)) {
        $error = 'Quantity must be a number.';
    } else {
        $quantity = (int) $quantity;
    }

    if (!empty($_FILES["product-img"]["tmp_name"])) {
        // Handle file upload if a file was uploaded
        $targetDir = "../assets/images/";
        $fileName = uniqid() . '_' . basename($_FILES["product-img"]["name"]);
        $targetFile = $targetDir . $fileName;
        move_uploaded_file($_FILES["product-img"]["tmp_name"], $targetFile);
        $imageUrl = "../assets/images/" . $fileName;
    }
    if (!$error) {
        // Insert into database
        $product->update($id, $seller_id, $name, $description, $price, $quantity, $imageUrl, $status);
        header('Location: product.php');
        ob_end_flush();
        exit();
    }
}
?>



<!-- Page body -->
<div class="page-body" id="edit_product">
    <div class="container-xl">
        <!-- Content here -->
        <form method="post" enctype="multipart/form-data">
            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error; ?>
                </div>
            <?php endif; ?>
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title"></h1>
                    <div class="row row-cards">
                        <div class="col-4 flex flex-wrap justify-center items-center">
                            <img id="preview" src="<?= $product_data['image_url']; ?>"
                                class="img-fiuld w-[200px] h-auto flex-1" />
                            <div class="d-flex justify-content-center mt-3 flex-1">
                                <div class="btn btn-outline-dark btn-rounded">
                                    <label class="form-label m-1" for="product-img">Choose file</label>
                                    <input type="hidden" name="product-img-prev"
                                        value="<?= $product_data['image_url']; ?>">
                                    <input type="file" class="form-control d-none" name="product-img" id="product-img"
                                        accept="image/*" multiple="false" />
                                </div>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="mb-3">
                                <label class="form-label required">Product Name</label>
                                <input type="text" class="form-control required" name="product-name"
                                    placeholder="Input placeholder" value="<?= $product_data['name']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Product Price</label>
                                <input type="text" class="form-control" name="product-price"
                                    placeholder="Input placeholder" min="0" value="<?= $product_data['price']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Product Quantity</label>
                                <input type="number" class="form-control" name="product-quantity"
                                    placeholder="Input placeholder" min="0" step="1"
                                    value="<?= $product_data['quantity']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Product Description</label>
                                <textarea class="form-control" data-bs-toggle="autosize" name="product-description"
                                    placeholder="Description" style="border: black 1px solid;" required><?= $product_data['description']; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Display</label>
                                <select class="form-control form-select" name="product-status">
                                    <option value="show" <?php if ($product_data['status'] === 'show') {
                                        echo "selected";
                                    } ?>>
                                        Show</option>
                                    <option value="hide" <?php if ($product_data['status'] === 'hide') {
                                        echo "selected";
                                    } ?>>
                                        Hide</option>
                                </select>
                            </div>
                            <div class="d-flex justify-content-end">
                                <input type="submit" class="btn btn-outline-green" value="Save" />
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