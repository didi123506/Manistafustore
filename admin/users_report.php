<?php
session_start();
include('../config/dbcon.php');
include('includes/header.php');

// Check if user is admin


// Fetch users data
$query = "SELECT * FROM users";
$result = mysqli_query($con, $query);
?>

<div class="container my-5">
    <h3>Users Report</h3>
    <div class="d-flex justify-content-end">
        <a href="index.php" class="btn btn-secondary">Back</a>
    </div>
    <?php if (mysqli_num_rows($result) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
             
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']); ?></td>
                        <td><?= htmlspecialchars($user['name']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td><?= htmlspecialchars($user['phone']); ?></td>
                   
                        <td><?= htmlspecialchars($user['created_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>
