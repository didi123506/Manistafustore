<?php
session_start();
include('../config/dbcon.php');
include('includes/header.php');

// Default: Show sales for the current year
$year = date('Y');
if (isset($_GET['year'])) {
    $year = $_GET['year'];
}

// SQL query to get monthly sales grouped by month for the selected year excluding canceled orders
$query = "SELECT MONTH(created_at) AS month, SUM(total_price) AS total_revenue, COUNT(id) AS total_orders 
          FROM orders 
          WHERE YEAR(created_at) = '$year' AND status != 4  -- Exclude canceled orders
          GROUP BY MONTH(created_at)";
$result = mysqli_query($con, $query);

// Initialize an array to store sales data for each month
$monthly_sales = array_fill(1, 12, ['total_revenue' => 0, 'total_orders' => 0]);

// Populate the array with actual sales data from the result
while ($row = mysqli_fetch_assoc($result)) {
    $monthly_sales[(int)$row['month']] = [
        'total_revenue' => $row['total_revenue'],
        'total_orders' => $row['total_orders'],
    ];
}

// Month names
$months = [
    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June',
    7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
];
?>

<div class="container my-5">
    <h3>Sales Report for <?= $year; ?></h3>
    <div class="d-flex justify-content-between">
        <a href="index.php" class="btn btn-secondary">Back</a>

        <!-- Year filter -->
        <form action="sales_report.php" method="GET" class="d-flex">
            <select name="year" class="form-select me-2">
                <?php for ($i = 2020; $i <= date('Y'); $i++): ?>
                    <option value="<?= $i; ?>" <?= $i == $year ? 'selected' : ''; ?>><?= $i; ?></option>
                <?php endfor; ?>
            </select>
            <button class="btn btn-outline-success" type="submit">Filter</button>
        </form>
    </div>

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Month</th>
                <th>Total Orders</th>
                <th>Total Revenue</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($months as $month_num => $month_name): ?>
                <tr>
                    <td><?= $month_name; ?></td>
                    <td><?= $monthly_sales[$month_num]['total_orders']; ?></td>
                    <td>₭<?= number_format($monthly_sales[$month_num]['total_revenue'], 0, '.', ','); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
