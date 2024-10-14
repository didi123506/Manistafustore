<?php
session_start();
?>
<?php 

include('../middleware/adminMiddleware.php');
include('includes/header.php');



?>


<div class="container my-2">
    <h1>Admin Dashboard</h1><br><br>
    <div class="row">
        <div class="col-md-3">
            <a href="users_report.php" class="btn btn-primary btn-block">Users Report</a>
        </div>
        <div class="col-md-3">
            <a href="sales_report.php" class="btn btn-success btn-block">Sales Report</a>
        </div>
        <div class="col-md-3">
            <a href="products_report.php" class="btn btn-info btn-block">Products Report</a>
        </div>
        <div class="col-md-3">
            <a href="orders_report.php" class="btn btn-warning btn-block">Orders Report</a>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
