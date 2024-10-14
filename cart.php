<?php
session_start();
include('config/dbcon.php');
include('functions/userfunctions.php');
include('includes/header.php');

if (!isset($_SESSION['auth_user']['user_id'])) {
    echo "<div class='container py-5'><h3>Please log in to view your cart</h3></div>";
    include('includes/footer.php');
    exit();
}

$user_id = $_SESSION['auth_user']['user_id'];
$query = "SELECT carts.*, products.name, products.selling_price, products.image 
          FROM carts 
          INNER JOIN products ON carts.prod_id = products.id 
          WHERE carts.user_id = '$user_id'";

$query_run = mysqli_query($con, $query);
?>

<div class="py-2" style="background-color:#a389dd ; color: white">
    <div class="container">
        <h6><a class="text-white" href="index.php">ໜ້າຫຼັກ </a> /
            <a class="text-white" href="cart.php">ກະຕ່າ</a>
        </h6>
    </div>
</div>

<div class="container py-4">
    <?php if (mysqli_num_rows($query_run) > 0): ?>
        <h3>ສິນຄ້າໃນກະຕ່າ</h3>
        <table class="table table-bordered">
            <thead class="text-center">
                <tr>
                    <th>ສິນຄ້າ</th>
                    <th>ຈຳນວນ</th>
                    <th>ລາຄາ</th>
                    <th>ລວມ</th>
                    <th>ຍົກເລິກ</th>
                </tr>
            </thead>
            <tbody>
                <?php $total_price = 0; ?>
                <?php while ($item = mysqli_fetch_assoc($query_run)): ?>
                    <?php $prod_total = $item['selling_price'] * $item['prod_qty']; ?>
                    <?php $total_price += $prod_total; ?>
                    <tr class="product_data">
                        <td class="text-center">
                            <img src="uploads/<?= $item['image']; ?>" alt="<?= $item['name']; ?>" width="50px" height="50px">
                            <?= $item['name']; ?>
                        </td>
                        <td>
                            <div class="input-group" >
                          
                                <input width="20px" type="text"  class="form-control text-center input-qty" value="<?= $item['prod_qty']; ?>"  readonly>
              
                            </div>
                        </td>
                        <td class="text-center"><?= number_format($item['selling_price'],0,',',','); ?>LAK</td>
                        <td class="text-center item-total"><?= number_format($prod_total, 0, '.', ','); ?>LAK</td>

                        <td class="text-center">
                            <button class="btn btn-danger removeFromCartBtn" value="<?= $item['prod_id']; ?>">Remove</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td class="text-center"><strong>ລວມທັງໝົດ:</strong> <span id="total-price"><?= number_format($total_price,0, '.', ','); ?>LAK</span></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        <div class="float-right">
            <a href="checkout.php" class="btn btn-outline-primary">ກົດເພື່ອຢືນຢັນ</a>
        </div>
    <?php else: ?>
        <div class="py-5 text-center"><h3>ບໍ່ມີສິນຄ້າໃນກະຕ່າ</h3></div>
    <?php endif; ?>
</div>





<?php include('includes/footer.php'); ?>
