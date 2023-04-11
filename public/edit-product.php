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
    $imageUrl = $product_data['image_url']; // set default image URL

    if (!empty($_FILES["product-img"]["tmp_name"])) {
        // Handle file upload if a file was uploaded
        $targetDir = "../assets/images/";
        $fileName = uniqid() . '_' . basename($_FILES["product-img"]["name"]);
        $targetFile = $targetDir . $fileName;
        move_uploaded_file($_FILES["product-img"]["tmp_name"], $targetFile);
        $imageUrl = "../assets/images/" . $fileName;
    }



    // Insert into database
    if ($product->update($id, $seller_id, $name, $description, $price, $imageUrl)) {
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
<div class="page-body">
    <div class="container-xl">
        <!-- Content here -->
        <form method="post" enctype="multipart/form-data">
            <div class="row row-cards">
                <div class="col-4">
                    <img id="preview" src="<?= $product_data['image_url']; ?>" class="img-fiuld" />
                    <div class="d-flex justify-content-center mt-3">
                        <div class="btn btn-outline-light btn-rounded">
                            <label class="form-label m-1" for="product-img">Choose file</label>
                            <input type="hidden" name="product-img-prev" value="<?= $product_data['image_url']; ?>">
                            <input type="file" class="form-control d-none" name="product-img" id="product-img"
                                onchange="previewImage(event)" accept="image/*" multiple="false" />
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" name="product-name" placeholder="Input placeholder" value="<?= $product_data['name']; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product Price</label>
                        <input type="text" class="form-control" name="product-price" placeholder="Input placeholder"
                            min="0" value="<?= $product_data['price']; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product Quantity</label>
                        <input type="number" class="form-control" name="product-quantity"
                            placeholder="Input placeholder" min="0" step="1" value="<?= $product_data['quantity']; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product Description</label>
                        <textarea class="form-control" data-bs-toggle="autosize" name="product-description"
                            placeholder="Description"><?= $product_data['description']; ?></textarea>
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