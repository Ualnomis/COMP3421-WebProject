<?php
require_once '../config/db_connection.php';
require_once '../classes/cart.class.php';
require_once '../classes/order.class.php';
require_once('../classes/product.class.php');

if (!isset($_SESSION['user_id'])) {
    echo '<script>window.location.replace("../public/");</script>';
    exit;
} else if ($_SESSION['role'] === 'seller') {
    echo '<script>window.location.replace("../public/");</script>';
    exit;
} else if ($_SESSION['role'] === 'buyer') {

}

$error_msg = "";
function isPostDataValid($post_data)
{
    $required_fields = [
        'order_id',
        'buyer-home-address',
        'buyer-city',
        'buyer-region',
        'buyer-first-name',
        'buyer-last-name',
        'buyer-phone'
    ];

    foreach ($required_fields as $field) {
        if (!isset($post_data[$field])) {
            return false;
        }
    }

    return true;
}

function processOrder($post_data, $conn)
{
    global $error_msg;
    $order = new Order($conn);
    $buyer_name = "{$post_data['buyer-first-name']} {$post_data['buyer-last-name']}";
    $buyer_address = "{$post_data['buyer-home-address']}, {$post_data['buyer-city']}, {$post_data['buyer-region']}";

    // Start transaction
    $conn->begin_transaction();

    // Check product quantity
    $order_items = $order->get_order_item_by_order_id($post_data['order_id'])['order_items'];
    $not_enough_quantity = false;
    foreach ($order_items as $order_item) {
        if (!$order->check_product_quantity($order_item['product_id'], $order_item['quantity'])) {
            $not_enough_quantity = true;
            break;
        }
        if ($order_item['status'] != 'show') {
            $conn->rollback(); // Rollback transaction
            $order->update_order($post_data['order_id'], $buyer_name, $post_data['buyer-phone'], $buyer_address, 4);
            $error_msg = "There are removed products.";
            return false;
        }
    }

    if ($not_enough_quantity) {
        $conn->rollback(); // Rollback transaction
        $order->update_order($post_data['order_id'], $buyer_name, $post_data['buyer-phone'], $buyer_address, 4);
        $error_msg = "Not enough Quantity";
        return false;
    } else {
        $product = new Product($conn);
        foreach ($order_items as $order_item) {
            $product->decrease_product_quantity($order_item['product_id'], $order_item['quantity']);
        }
        $order->update_order($post_data['order_id'], $buyer_name, $post_data['buyer-phone'], $buyer_address, 2);
        $conn->commit(); // Commit transaction
        return true;
    }
}


$error = false;
$errorMessage = '';

if (isPostDataValid($_POST)) {
    $buyerFirstName = $_POST['buyer-first-name'];
    $buyerLastName = $_POST['buyer-last-name'];
    $buyerPhone = $_POST['buyer-phone'];
    $buyerHomeAddress = $_POST['buyer-home-address'];
    $buyerCity = $_POST['buyer-city'];
    $buyerRegion = $_POST['buyer-region'];
    $cardNumber = $_POST['cardnumber'];
    $cardExpiry = $_POST['cardexpiry'];
    $cardCVV = $_POST['cardcvv'];
    
    // Validate card CVV
    if (empty($cardCVV)) {
        $error = true;
        $errorMessage = 'Please enter your card CVV.';
    } elseif (!preg_match('/^\d{3}$/', $cardCVV)) {
        $error = true;
        $errorMessage = 'Card CVV must be exactly 3 digits long.';
    }

    // Validate card expiry date
    if (empty($cardExpiry)) {
        $error = true;
        $errorMessage = 'Please enter your card expiry date.';
    } elseif (!preg_match('/^(0[1-9]|1[0-2])\/?([0-9]{2})$/', $cardExpiry)) {
        $error = true;
        $errorMessage = 'Card expiry date must be in the format MM/YY.';
    }

    // Validate card number
    if (empty($cardNumber)) {
        $error = true;
        $errorMessage = 'Please enter your card number.';
    } elseif (!preg_match('/^\d{16}$/', $cardNumber)) {
        $error = true;
        $errorMessage = 'Card number must be exactly 16 digits long.';
    }
    
    // Validate buyer's region
    if (empty($buyerRegion)) {
        $error = true;
        $errorMessage = 'Please select your region.';
    }

    // Validate buyer's city
    if (empty($buyerCity)) {
        $error = true;
        $errorMessage = 'Please enter your city.';
    }

    // Validate buyer's home address
    if (empty($buyerHomeAddress)) {
        $error = true;
        $errorMessage = 'Please enter your home address.';
    }

    // Validate buyer's phone number
    if (empty($buyerPhone)) {
        $error = true;
        $errorMessage = 'Please enter your phone number.';
    } elseif (!preg_match('/^[0-9]{8}$/', $buyerPhone)) {
        $error = true;
        $errorMessage = 'Phone number can only contain 8 digits.';
    }

    // Validate buyer's last name
    if (empty($buyerLastName)) {
        $error = true;
        $errorMessage = 'Please enter your last name.';
    }

    // Validate buyer's first name
    if (empty($buyerFirstName)) {
        $error = true;
        $errorMessage = 'Please enter your first name.';
    }


    if ($error) {
        $_SESSION['error'] = true;
        $_SESSION['error_message'] = $errorMessage;
        $_SESSION['form_data'] = $_POST; // Store the form data

        header('Location: ../public/checkout.php?order_id=' . $_POST['order_id']); // Redirect back to the checkout page
        exit();
    }

    if (processOrder($_POST, $conn)) {
        echo '<script>window.location.replace("../public/order-list.php");</script>';
    } else {
        echo <<<HTML
        <script>
            alert("Order Cancelled: {$error_msg}");
            window.location.replace("../public/product.php");
        </script>
        HTML;
    }

} else {
    echo '<script>window.location.replace("../public/checkout.php?order_id=' . $order_id . '&success=fail");</script>';
}
?>