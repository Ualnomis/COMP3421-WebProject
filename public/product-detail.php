<?php
$title = "Gift Detail";
$styles = "";

include_once('../includes/header.inc.php');
include_once('../classes/product.class.php');
include_once('../classes/review.class.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $product = new Product($conn);
    $product_result = $product->select_one($id);
    if ($product_result->num_rows > 0) {
        $product_data = $product_result->fetch_assoc();
    } else {
        echo '<script>window.location.replace("product.php");</script>';
        exit();
    }
} else {
    echo '<script>window.location.replace("product.php");</script>';
    exit();
}

// Create a new instance of the Review class with a database connection
$review = new Review($conn);

$page_title = "Gift Detail";

include_once('../includes/navbar.inc.php');
include_once('../includes/page-wrapper-start.inc.php');

// Get the current page number from the URL or set it to 1 if not specified
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;

// Set the number of reviews to display per page
$reviews_per_page = 3;

// Calculate the offset for the current page
$offset = ($current_page - 1) * $reviews_per_page;

// Get the reviews for the current product and page
$reviews = $review->selectByProductId($id, $reviews_per_page, $offset);


function getTotalReviews($conn, $id)
{
    $stmt = $conn->prepare("SELECT COUNT(*) as total_reviews FROM reviews WHERE product_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total_reviews'];
}

function renderActionButton($product_data)
{
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'seller') {
        return <<<HTML
        <div class="row mt-3">
            <a href="./edit-product.php?id={$_GET['id']}" class="btn btn-outline-light">
                Edit
            </a>
            <a class="btn btn-danger  mt-3" href="./delete-product.php?id={$_GET['id']}">Delete</a>
        </div>
    HTML;
    } else {
        if ($product_data['quantity'] != 0) {
            return <<<HTML
                <div class="row mt-3">
                    <button type="submit" class="btn btn-outline-light">
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

}
?>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div id="alert-container"></div>
        <div class="row">
            <div class="col-md-8 col-sm-12 flex justify-center items-center">
                <img src="<?= $product_data['image_url']; ?>" class="w-[300px] h-auto" />
            </div>
            <div class="col-md-4 col-sm-12">
                <form method="post" id="add-to-cart-form">
                    <div class="mb-3">
                        <input type="hidden" name="product-id" value="<?= $product_data['id']; ?>" />
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" value="<?= $product_data['name']; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <input type="hidden" name="product-id" value="<?= $product_data['id']; ?>" />
                        <label class="form-label">Product Description:</label>
                        <input type="text" class="form-control" value="<?= $product_data['description']; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <input type="hidden" name="product-id" value="<?= $product_data['id']; ?>" />
                        <label class="form-label">HK$</label>
                        <input type="text" class="form-control" value="<?= $product_data['price']; ?>" readonly>
                    </div>

                    <?php if ((isset($_SESSION['role']) && $_SESSION['role'] === 'seller')): ?>
                        <div class="mb-3">
                            <input type="hidden" name="product-id" value="<?= $product_data['id']; ?>" />
                            <label class="form-label">Quantity</label>
                            <input type="text" class="form-control" value="<?= $product_data['quantity']; ?>" readonly>
                        </div>
                    <?php endif; ?>




                    <?php if (!(isset($_SESSION['role']) && $_SESSION['role'] === 'seller')): ?>
                        <div class="mt-3">
                            <label class="form-label">Quantity</label>
                            <div class="input-group w-50">
                                <button class="btn btn-outline-light" id="btn-minus-quantity"
                                    <?= $product_data['quantity'] == 0 ? "disabled" : ""; ?>>
                                    -
                                </button>
                                <input type="number" class="form-control" name="order-quantity"
                                    value="<?= $product_data['quantity'] == 0 ? $product_data['quantity'] : 1 ?>" min="1"
                                    max="<?= $product_data['quantity']; ?>" step="1" <?= $product_data['quantity'] == 0 ? "disabled" : ""; ?> pattern="[0-9]*">
                                <button class="btn btn-outline-light" id="btn-add-quantity" <?= $product_data['quantity'] == 0 ? "disabled" : ""; ?>>
                                    +
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?= renderActionButton($product_data); ?>
                </form>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'buyer'): ?>
                    <div class="row mt-3">
                        <h2>Add a Review</h2>
                        <form method="post" action="submit-review.php">
                            <input type="hidden" name="product_id" value="<?= $product_data['id']; ?>">
                            <div class="mb-3">
                                <label for="rating" class="form-label">Rating</label>
                                <select name="rating" id="rating" class="form-control" required>
                                    <option value="">Choose a Rating</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="review_text" class="form-label">Review</label>
                                <textarea name="review_text" id="review_text" class="form-control" rows="5"
                                    required></textarea>
                            </div>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit
                                Review</button>
                        </form>
                        <div class="row mt-5">
                            <h2>Reviews</h2>
                            <?php while ($row = $reviews->fetch_assoc()): ?>
                                <div class="col-12 mt-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <?= $row['rating']; ?> / 5
                                            </h5>
                                            <p class="card-text">
                                                <?= $row['review_text']; ?>
                                            </p>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    <?= $row['created_at']; ?>
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>

                            <?php
                            $total_reviews = getTotalReviews($conn, $id);
                            $total_pages = ceil($total_reviews / $reviews_per_page);
                            ?>

                            <?php if ($total_pages > 1): ?>
                                <div class="d-flex justify-content-center">
                                    <?php if ($current_page > 1): ?>
                                        <a href="?id=<?= $id; ?>&page=<?= $current_page - 1; ?>"
                                            class="btn btn-outline-light mt-3">Previous</a>
                                    <?php endif; ?>
                                    <?php if ($current_page < $total_pages): ?>
                                        <a href="?id=<?= $id; ?>&page=<?= $current_page + 1; ?>"
                                            class="btn btn-outline-light mt-3">Next</a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    include_once('../includes/page-wrapper-end.inc.php');
    $scripts = <<<HTML
<!-- <script defer src="../assets/js/product-detail.js"></script> -->
HTML;
    $modals = <<<HTML
HTML;
    include_once('../includes/footer.inc.php');
    ?>