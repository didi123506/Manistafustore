<?php
session_start();
include('../config/dbcon.php');
include('includes/header.php');

// Search functionality
$search_term = '';
if (isset($_GET['search'])) {
    $search_term = mysqli_real_escape_string($con, $_GET['search']);
    $query = "SELECT o.*, u.name AS username, u.id AS user_id 
              FROM orders o
              JOIN users u ON o.user_id = u.id
              WHERE u.name LIKE '%$search_term%' OR o.tracking_no LIKE '%$search_term%'
              ORDER BY o.created_at DESC";
} else {
    $query = "SELECT o.*, u.name AS username, u.id AS user_id 
              FROM orders o
              JOIN users u ON o.user_id = u.id
              ORDER BY o.created_at DESC";
}

$result = mysqli_query($con, $query);
?>

<div class="container my-5">
    <h3>Orders Report</h3>
    <div class="d-flex justify-content-between">
        <a href="index.php" class="btn btn-secondary">Back</a>
        <form action="orders_report.php" method="GET" class="d-flex">
            <input class="form-control me-2" type="search" name="search" placeholder="Search by username or tracking number" value="<?= htmlspecialchars($search_term); ?>">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    </div>

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User Name</th>
                <th>Tracking No</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($order = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['id']); ?></td>
                        <td><?= htmlspecialchars($order['username']); ?></td>
                        <td><?= htmlspecialchars($order['tracking_no']); ?></td>
                        <td>₭<?= number_format($order['total_price'], 0, '.', ','); ?></td>
                        <td>
                            <?php
                            // Display status
                            switch ($order['status']) {
                                case 0: echo 'ຄຳສັ່ງຊື້'; break;
                                case 1: echo 'ກຳລັງກວດສອບ'; break;
                                case 2: echo 'ກຳລັງກຽມສິນຄ້າ'; break;
                                case 3: echo 'ຈັດສົ່ງສຳເລັດ'; break;
                                case 4: echo 'ລາຍການຖືກຍົກເລິກ'; break;
                                default: echo 'Unknown'; break;
                            }
                            ?>
                        </td>
                        <td><?= date("F d, Y", strtotime($order['created_at'])); ?></td>
                        <td><a href="view_admin_order.php?order_id=<?= $order['id']; ?>" class="btn btn-info">View Details</a></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No orders found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
