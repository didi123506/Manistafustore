<?php
session_start();
include('config/dbcon.php');
include('functions/userfunctions.php');
include('includes/header.php');

if (!isset($_SESSION['auth'])) {
    $_SESSION['message'] = "Please login to view order details.";
    header('Location: login.php');
    exit;
}

// Check if order ID is set in the query string
if (!isset($_GET['order_id'])) {
    echo "<div class='container py-5'><h3>No order specified.</h3></div>";
    include('includes/footer.php');
    exit;
}

$order_id = mysqli_real_escape_string($con, $_GET['order_id']);
$user_id = $_SESSION['auth_user']['user_id'];

// Retrieve order details
$order_query = "SELECT * FROM orders WHERE id = '$order_id' AND user_id = '$user_id'";
$order_result = mysqli_query($con, $order_query);
$order_details = mysqli_fetch_assoc($order_result);

if (!$order_details) {
    echo "<div class='container py-5'><h3>Order not found or you do not have permission to view it.</h3></div>";
    include('includes/footer.php');
    exit;
}

// Retrieve items for this order and join with the products table to get product names
$items_query = "SELECT oi.qty, oi.price, p.name FROM order_items oi JOIN products p ON oi.prod_id = p.id WHERE oi.order_id = '$order_id'";
$items_result = mysqli_query($con, $items_query);

?>
<div class="container my-5">
    <div class="d-flex justify-content-end">
         <a href="my-orders.php" class="btn btn-secondary">ກັບຄືນ</a>
        </div> 
        <h3>ລາຍລະອຽດການສັ່ງຊື້</h3>
        <div class="card shadow">
            <div class="card-body">
                <div class="d-flex justify-content-end">
                    <h>ຊຳລະເງິນ:</h4>
                <img src="uploads/<?= htmlspecialchars($order_details['image_file']); ?>" alt="Order Image" style="width: 100px; height: auto;">
                </div>
            <h5 class="card-title">ເລກສັ່ງຊື້ #<?= htmlspecialchars($order_details['tracking_no']); ?><br>
            <strong>ລາຄາທັງໝົດ:</strong> ₭<?= htmlspecialchars(number_format($order_details['total_price'], 0, '.', ',')); ?></h5>
            <p class="card-text">
                
            
                <strong>ວັນທີ:</strong> <?= date("F d, Y", strtotime($order_details['created_at'])); ?><br>
                <strong>ຊື່:</strong> <?= htmlspecialchars($order_details['name']); ?><br>
                <strong>ເບີໂທ:</strong> <?= htmlspecialchars($order_details['phone']); ?><br>
                <strong>ສາຂາຂົນສົ່ງ:</strong> <?= htmlspecialchars($order_details['address']); ?><br>
             
                
            </p>
            <table class="table">
                <thead>
                    <tr>
                        <th>ສິນຄ້າ</th>
                        <th>ຈຳນວນ</th>
                        <th>ລາຄາສິນຄ້າ</th>
                        <th>ລາຄາລວມສິນຄ້າ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']); ?></td>
                            <td><?= $item['qty']; ?></td>
                            <td><?= number_format($item['price'], ); ?>LAK</td>
                            <td><?= number_format($item['price'] * $item['qty'], ); ?>LAK</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>
