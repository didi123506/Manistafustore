<?php
session_start();
include('../config/dbcon.php');
include('includes/header.php');

// Handle order status update
if (isset($_POST['update_status_btn'])) {
    $order_id = mysqli_real_escape_string($con, $_POST['order_id']);
    $new_status = mysqli_real_escape_string($con, $_POST['status']);  // This will be numeric now

    // Fetch the current status of the order
    $order_status_query = "SELECT status FROM orders WHERE id='$order_id'";
    $order_status_result = mysqli_query($con, $order_status_query);
    $current_status = mysqli_fetch_assoc($order_status_result)['status'];

    // If new status is "Cancel", restore the product quantities
    if ($new_status == 4 && $current_status != 4) {
        // Fetch ordered products for this order
        $order_items_query = "SELECT prod_id, qty FROM order_items WHERE order_id='$order_id'";
        $order_items_result = mysqli_query($con, $order_items_query);

        while ($item = mysqli_fetch_assoc($order_items_result)) {
            $product_id = $item['prod_id'];
            $ordered_qty = $item['qty'];

            // Restore product quantity in the `products` table
            $update_product_qty_query = "UPDATE products SET qty = qty + '$ordered_qty' WHERE id='$product_id'";
            mysqli_query($con, $update_product_qty_query);
        }
    }

    // If new status is "Under process", "Preparing", or "Complete", decrease product quantities
    if (in_array($new_status, [1, 2, 3]) && $current_status != $new_status) {
        // Fetch ordered products for this order
        $order_items_query = "SELECT prod_id, qty FROM order_items WHERE order_id='$order_id'";
        $order_items_result = mysqli_query($con, $order_items_query);

        while ($item = mysqli_fetch_assoc($order_items_result)) {
            $product_id = $item['prod_id'];
            $ordered_qty = $item['qty'];

            // Decrease product quantity in the `products` table
            $update_product_qty_query = "UPDATE products SET qty = qty - '$ordered_qty' WHERE id='$product_id' AND qty >= '$ordered_qty'";
            mysqli_query($con, $update_product_qty_query);
        }
    }

    // Update the status in the database
    $update_query = "UPDATE orders SET status='$new_status' WHERE id='$order_id'";
    $update_query_run = mysqli_query($con, $update_query);

    if ($update_query_run) {
        $_SESSION['message'] = "Order status updated successfully!";
    } else {
        $_SESSION['message'] = "Failed to update order status.";
    }

    // Redirect back to the order management page
    header("Location: admin_orders.php");
    exit();
}

// Handle order deletion
if (isset($_POST['delete_order_btn'])) {
    $order_id = mysqli_real_escape_string($con, $_POST['order_id']);

    // First, delete the order items associated with the order
    $delete_order_items_query = "DELETE FROM order_items WHERE order_id='$order_id'";
    $delete_order_items_run = mysqli_query($con, $delete_order_items_query);

    if ($delete_order_items_run) {
        // Then, delete the order itself
        $delete_order_query = "DELETE FROM orders WHERE id='$order_id'";
        $delete_order_run = mysqli_query($con, $delete_order_query);

        if ($delete_order_run) {
            $_SESSION['message'] = "Order deleted successfully!";
        } else {
            $_SESSION['message'] = "Failed to delete the order.";
        }
    } else {
        $_SESSION['message'] = "Failed to delete order items.";
    }

    // Redirect back to the order management 
}

// Fetch all orders
$query = "SELECT * FROM orders ORDER BY created_at DESC";
$result = mysqli_query($con, $query);
?>

<div class="container my-5">
    <h3>Order Management</h3>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['message']; ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Tracking No</th>
                    <th>Total Price</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Update Status</th>
                    <th>Details</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['id']); ?></td>
                        <td><?= htmlspecialchars($order['tracking_no']); ?></td>
                        <td><?= number_format($order['total_price'], 0); ?> LAK</td>
                        <td><?= date("F d, Y", strtotime($order['created_at'])); ?></td>
                        <td>
                            <?php
                            // Display status as human-readable text
                            switch ($order['status']) {
                                case 1: echo "Under process"; break;
                                case 2: echo "Preparing"; break;
                                case 3: echo "Complete"; break;
                                case 4: echo "Cancel"; break;
                                default: echo "Unknown"; break;
                            }
                            ?>
                        </td>
                        <td>
                            <form action="admin_orders.php" method="POST">
                                <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                                <select name="status" class="form-control">
                                    <option value="1" <?= $order['status'] == 1 ? 'selected' : ''; ?>>Under process</option>
                                    <option value="2" <?= $order['status'] == 2 ? 'selected' : ''; ?>>Preparing</option>
                                    <option value="3" <?= $order['status'] == 3 ? 'selected' : ''; ?>>Complete</option>
                                    <option value="4" <?= $order['status'] == 4 ? 'selected' : ''; ?>>Cancel</option>
                                </select>
                                <button type="submit" name="update_status_btn" class="btn btn-primary mt-2">Update</button>
                            </form>
                        </td>
                        <td><a href="view_admin_order.php?order_id=<?= $order['id']; ?>" class="btn btn-info">View Details</a></td>
                        <td>
                            <form action="admin_orders.php" method="POST">
                                <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                                <button type="submit" name="delete_order_btn" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>
