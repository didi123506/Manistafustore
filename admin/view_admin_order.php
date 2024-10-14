<?php
session_start();
include('../config/dbcon.php');
include('includes/header.php');

// Check if order_id is set in the query string
if (!isset($_GET['order_id'])) {
    echo "<div class='container py-5'><h3>No order specified.</h3></div>";
    include('includes/footer.php');
    exit();
}

$order_id = mysqli_real_escape_string($con, $_GET['order_id']);

// Retrieve order details
$order_query = "SELECT * FROM orders WHERE id = '$order_id'";
$order_result = mysqli_query($con, $order_query);
$order_details = mysqli_fetch_assoc($order_result);

// Retrieve order items along with product image
$items_query = "SELECT oi.qty, oi.price, p.name, p.image 
                FROM order_items oi 
                JOIN products p ON oi.prod_id = p.id 
                WHERE oi.order_id = '$order_id'";
$items_result = mysqli_query($con, $items_query);
?>

<div class="container my-5">
    <h3>Order Details (Order ID: <?= htmlspecialchars($order_details['id']); ?>)</h3>
    <div class="d-flex justify-content-end">
        <a href="admin_orders.php" class="btn btn-secondary">Back</a>
    </div>
    <div class="card shadow">
        <div class="card-body">
            <!-- Image and Modal Trigger -->

            
            <!-- Modal for enlarged image -->
            <div id="imageModal" class="modal">
                <span class="close" onclick="closeMediumImage()">&times;</span>
                <img class="modal-content" id="mediumImage">
            </div>
            
            <h5 class="card-title">Tracking No: <?= htmlspecialchars($order_details['tracking_no']); ?> <br>
            <strong>Total Price:</strong> ₭<?= htmlspecialchars(number_format($order_details['total_price'], 0, '.', ',')); ?></h5>
            
            <p class="card-text">
                <strong>Date:</strong> <?= date("F d, Y", strtotime($order_details['created_at'])); ?><br>
                <strong>Name:</strong> <?= htmlspecialchars($order_details['name']); ?><br>
                <strong>Phone:</strong> <?= htmlspecialchars($order_details['phone']); ?><br>
                <strong>Address:</strong> <?= htmlspecialchars($order_details['address']); ?>
            </p>
            <div class="image-container">
                <h5>Payment:</h5>
                <img id="orderImage" src="../uploads/<?= htmlspecialchars($order_details['image_file']); ?>" alt="Order Image" style="width: 100px; height: auto; cursor: pointer;" onclick="showMediumImage()">
            </div><br>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']); ?></td>
                            <td>
                                <img src="../uploads/<?= htmlspecialchars($item['image']); ?>" alt="<?= htmlspecialchars($item['name']); ?>" width="50px" height="50px">
                            </td>
                            <td><?= $item['qty']; ?></td>
                            <td>₭<?= number_format($item['price'], 0, '.', ','); ?></td>
                            <td>₭<?= number_format($item['price'] * $item['qty'], 0, '.', ','); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- JavaScript for Modal -->
<script>
function showMediumImage() {
    var modal = document.getElementById("imageModal");
    var img = document.getElementById("orderImage");
    var modalImg = document.getElementById("mediumImage");

    modal.style.display = "block";
    modalImg.src = img.src;
}

function closeMediumImage() {
    var modal = document.getElementById("imageModal");
    modal.style.display = "none";
}
</script>

<!-- Modal CSS -->
<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    padding-top: 100px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
}

.modal-content {
    margin: auto;
    display: block;
    width: 50%; /* Medium size */
    max-width: 600px; /* Medium max width */
}

.close {
    position: absolute;
    top: 20px;
    right: 35px;
    color: white;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
}
</style>

<?php include('includes/footer.php'); ?>
