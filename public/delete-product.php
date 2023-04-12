<?php

include_once '../config/db_connection.php';
require_once '../classes/product.class.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'seller') {
    header('Location: ./index.php');
    exit();
}

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Initialize a new Product instance
    $product = new Product($conn);

    // Call the delete function
    $product->delete($product_id);


    header('Location: ./product.php?deleted=true');
    exit();
}
?>