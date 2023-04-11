<?php
session_start();
include_once '../config/db_connection.php';
include_once('../classes/review.class.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];

    $review = new Review($conn);
    $review->insert($user_id, $product_id, $rating, $review_text);

    header("Location: product-detail.php?id=$product_id");
} else {
    header("Location: product.php");
}
?>
