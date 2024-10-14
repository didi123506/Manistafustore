<?php
session_start();
include('../config/dbcon.php');
include('includes/header.php');



// Fetch products data
$query = "SELECT * FROM products";
$result = mysqli_query($con, $query);
?>

<div class="container my-5">
    <h3>Products Report</h3>
    <div class="d-flex justify-content-end">
        <a href="index.php" class="btn btn-secondary">Back</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($product = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($product['id']); ?></td>
                    <td><?= htmlspecialchars($product['name']); ?></td>
                    <td>₭<?= number_format($product['selling_price'], 0, '.', ','); ?></td>
                    <td><?= htmlspecialchars($product['qty']); ?></td>
                    <td><?= $product['status'] == '1' ? 'Inactive' : ' Active'; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
