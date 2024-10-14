<?php
session_start();
include('../config/dbcon.php');

// Ensure the user is authenticated before placing an order
if (!isset($_SESSION['auth_user']['user_id'])) {
    $_SESSION['message'] = "You need to log in to place an order.";
    header('Location: ../login.php');
    exit;
}

if (isset($_POST['placeOrderBtn'])) {
    $userid = $_SESSION['auth_user']['user_id'];
    $name = mysqli_real_escape_string($con, trim($_POST['name']));
    $phone = mysqli_real_escape_string($con, trim($_POST['phone']));
    $address = mysqli_real_escape_string($con, trim($_POST['address']));
    $payment_mode = mysqli_real_escape_string($con, 'COD'); // Assuming 'COD' is set, adjust as needed.

    // Validate inputs
    if (empty($name) || empty($phone) || empty($address)) {
        $_SESSION['message'] = "All fields are mandatory.";
        header('Location: ../checkout.php');
        exit;
    }

    // Image upload handling
    $imagePath = '';
    if (isset($_FILES['orderImage']['name']) && $_FILES['orderImage']['name'] != '') {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES['orderImage']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $uploadOk = 1;

        // Check if image file is an actual image or fake image
        $check = getimagesize($_FILES['orderImage']['tmp_name']);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $_SESSION['message'] = "File is not an image.";
            $uploadOk = 0;
        }

        if ($_FILES['orderImage']['size'] > 500000) {
            $_SESSION['message'] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            $_SESSION['message'] = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES['orderImage']['tmp_name'], $target_file)) {
                $imagePath = $target_file;
            } else {
                $_SESSION['message'] = "Sorry, there was an error uploading your file.";
                header('Location: ../checkout.php');
                exit;
            }
        }
    }

    // Begin transaction
    mysqli_begin_transaction($con);
    try {
        $total_price = 0;
        $cart_query = "SELECT c.prod_id, c.prod_qty, p.selling_price, p.qty as available_qty 
                       FROM carts c 
                       JOIN products p ON c.prod_id = p.id 
                       WHERE c.user_id = '$userid'";
        $cart_query_run = mysqli_query($con, $cart_query);

        // Check and decrease product quantities based on cart items
        while ($item = mysqli_fetch_assoc($cart_query_run)) {
            $total_price += $item['selling_price'] * $item['prod_qty'];

            if ($item['available_qty'] < $item['prod_qty']) {
                throw new Exception("Insufficient stock for product ID {$item['prod_id']}");
            }

            // Decrease product quantity
            $update_product_qty_query = "UPDATE products SET qty = qty - {$item['prod_qty']} WHERE id = {$item['prod_id']}";
            mysqli_query($con, $update_product_qty_query);
        }

        // Generate a unique tracking number
        $tracking_no = "MF" . time() . rand(1000, 9999);

        // Insert order into the orders table
        $insert_query = "INSERT INTO orders (user_id, name, phone, address, total_price, payment_mode, image_file, tracking_no, status) 
                         VALUES ('$userid', '$name', '$phone', '$address', '$total_price', '$payment_mode', '$imagePath', '$tracking_no', 0)";
        $insert_query_run = mysqli_query($con, $insert_query);

        if (!$insert_query_run) {
            throw new Exception("Failed to insert order: " . mysqli_error($con));
        }

        $order_id = mysqli_insert_id($con);

        // Insert each cart item into the order_items table
        foreach ($cart_query_run as $item) {
            $insert_item_query = "INSERT INTO order_items (order_id, prod_id, qty, price) 
                                  VALUES ('$order_id', '{$item['prod_id']}', '{$item['prod_qty']}', '{$item['selling_price']}')";
            if (!mysqli_query($con, $insert_item_query)) {
                throw new Exception("Failed to insert order item: " . mysqli_error($con));
            }
        }

        // Clear the cart after placing the order
        $clear_cart_query = "DELETE FROM carts WHERE user_id = '$userid'";
        mysqli_query($con, $clear_cart_query);

        // Commit the transaction
        mysqli_commit($con);
        $_SESSION['message'] = "Order placed successfully!";
        header('Location: ../my-orders.php');
        exit;
    } catch (Exception $e) {
        mysqli_rollback($con);
        $_SESSION['message'] = "Order placement failed: " . $e->getMessage();
        header('Location: ../checkout.php');
        exit;
    }
} else {
    header('Location: ../index.php');
    exit;
}
?>
