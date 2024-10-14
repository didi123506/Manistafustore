<?php
session_start();
include('config/dbcon.php');
include('functions/userfunctions.php');
include('includes/header.php');

            if (!isset($_SESSION['auth'])) {
                $_SESSION['message'] = "Pleasee login to view your orders.";
                header('Location: login.php');
                exit;
            }

            $user_id = $_SESSION['auth_user']['user_id'];
            $query = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY created_at DESC";
            $result = mysqli_query($con, $query);
            ?>

<div class="container my-5">
    <h3>ປະວັດການສັ່ງຊື້</h3>
    <?php 
        $order = getOrders();
    if (mysqli_num_rows($result) > 0):
        
        
        ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Tracking No</th>
                    <th>ລາຄາທັງໝົດ</th>
                    <th>ວັນທີ</th>
                    <th>ສະຖານະ</th> <!-- Add a column for status -->
                    <th>ລາຍລະອຽດ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = mysqli_fetch_assoc($result)): ?>
                
                    <tr>
                    <td><?= htmlspecialchars($order['id']); ?></td>
                    <td><?= htmlspecialchars($order['tracking_no']); ?></td>
                    <td><?= number_format($order['total_price'],0); ?>LAK</td>               
                    <td><?= htmlspecialchars(date('F d, Y', strtotime($order['created_at']))); ?></td>
                    <td>
                            <!-- Display the status here -->
                            <?php
                                // Status handling: Translate tinyint(4) to readable status
                                if ($order['status'] == 0) {
                                    
                                    echo 'ສັ່ງຊື້ແລ້ວ';
                                }
                                 elseif ($order['status'] == 1) {
                                    echo 'ກຳລັງກວດສອບ';
                                } 
                         
                                elseif ($order['status'] == 2) {
                                    echo 'ກຳລັງກຽມສິນຄ້າ';
                                }
                                 elseif ($order['status'] == 3) {
                                    echo 'ຈັດສົ່ງສຳເລັດ';
                                } 
                                 elseif ($order['status'] == 4) {
                                    echo 'ລາຍການຖືກຍົກເລິກ';
                                } 
                                else {
                                    echo 'Unknown';
                                }
                            ?>
                        </td>
                    <td><a href="view-order.php?order_id=<?= $order['id']; ?>" class="btn btn-primary btn-sm">ເບິ່ງລາຍລະອຽດ</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no orders yet.</p>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>
