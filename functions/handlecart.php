<?php
session_start();
include('../config/dbcon.php'); // Make sure this path is correct

if (!isset($_SESSION['auth'])) {
    echo 401; // User not authenticated
    exit;
}

$user_id = $_SESSION['auth_user']['user_id']; // Ensure you have user_id in your session

// Check if required POST variables are set
if (!isset($_POST['prod_id']) || !isset($_POST['scope'])) {
    echo 400; // Bad request
    exit;
}

$prod_id = $_POST['prod_id'];
$scope = $_POST['scope'];

// Fetch product details to validate stock and price
$product_query = "SELECT * FROM products WHERE id='$prod_id' AND status='0'";
$product_query_run = mysqli_query($con, $product_query);
$product = mysqli_fetch_assoc($product_query_run);

if (!$product) {
    echo 404; // Product not found or inactive
    exit;
}

switch ($scope) {
    case 'add':
        if (!isset($_POST['prod_qty'])) {
            echo 400; // Bad request for add without quantity
            exit;
        }

        $prod_qty = $_POST['prod_qty'];

        // Validate if the requested quantity is available in stock
        if ($prod_qty > $product['qty']) {
            echo "stock_unavailable"; // Requested quantity exceeds available stock
            exit;
        }

        // Check if the product already exists in the cart
        $check = "SELECT * FROM carts WHERE prod_id = '$prod_id' AND user_id = '$user_id'";
        $check_run = mysqli_query($con, $check);

        if (mysqli_num_rows($check_run) > 0) {
            echo "existing"; // Product already in the cart
        } else {
            // Insert new item into the cart
            $insert = "INSERT INTO carts (user_id, prod_id, prod_qty) VALUES ('$user_id', '$prod_id', '$prod_qty')";
            if (mysqli_query($con, $insert)) {
                echo 201; // Successfully added
            } else {
                echo 500; // Error occurred
            }
        }
        break;

    case 'remove':
        // Remove the item from the cart
        $delete = "DELETE FROM carts WHERE user_id = '$user_id' AND prod_id = '$prod_id'";
        if (mysqli_query($con, $delete)) {
            echo 200; // Successfully removed
        } else {
            echo 500; // Error occurred
        }
        break;

    default:
        echo 400; // Bad request
        break;
}
?>
