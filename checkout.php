<?php
session_start();
include('config/dbcon.php');
include('functions/userfunctions.php');
include('includes/header.php');

// Ensure user is authenticated
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

<div class="py-3" style="background-color:#a389dd; ; color: white"> 
    <div class="container">
        <h6>
            <a class="text-white" href="index.php">ໜ້າຫຼັກ </a>/
            <a class="text-white" href="checkout.php">ຢືນຢັນການສັ່ງຊື້</a>
        </h6>
    </div>
</div>

<div class="container my-5">
    <?php if (mysqli_num_rows($query_run) > 0): ?>
        <div class="card shadow">
         
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <h4>ລາຍລະອຽດຄຳສັ່ງຊື້</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ສິນຄ້າ</th>
                                    <th>ລາຄາ</th>
                                    <th>ຈຳນວນ</th>
                                    <th>ລວມ</th>
                                </tr>
                            </thead>
                            <tbody id="cart-items">
                                <?php $total_price = 0; ?>
                                <?php while ($item = mysqli_fetch_assoc($query_run)): ?>
                                    <?php $prod_total = $item['selling_price'] * $item['prod_qty']; ?>
                                    <?php $total_price += $prod_total; ?>
                                    <tr>
                                        <td>
                                            <img src="uploads/<?= $item['image']; ?>" alt="<?= $item['name']; ?>" width="50px">
                                            <?= $item['name']; ?>
                                        </td>
                                        <td><?= number_format($item['selling_price'], 0, '.', ','); ?> LAK</td>
                                        <td><?= $item['prod_qty']; ?></td>
                                        <td><?= number_format($prod_total, 0, '.', ','); ?> LAK</td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">ລວມທັງໝົດ:</th>
                                    <th><?= number_format($total_price, 0, '.', ','); ?> LAK</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="col-md-7">
                        <h4>ບ່ອນຈັດສົ່ງ (ກອກຊື່ ເບີໂທ ທີ່ຢູ່ສາຂາຂົນສົ່ງ) </h4>
                        <form action="functions/placeorder.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="name" class="fw-bold">ຊື່</label>
                                <input type="text" class="form-control" name="name" placeholder="ປ້ອນຊື່" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="fw-bold">ເບີໂທ</label>
                                <input type="text" class="form-control" name="phone" placeholder="ປ້ອນເບີໂທ" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="fw-bold">ສາຂາຂົນສົ່ງ </label> (ຮຸ່ງອາລຸນ,ອານຸສິດ,ມີໄຊ...)* ກະລຸນາລະບຸສາຂາຢ່າງລະອຽດ*
                                <textarea name="address" class="form-control" rows="5" required></textarea>
                            </div>

            <div class="mb-2">
    <label for="bank-number" class="form-label">ຊື່ບັນຊີ: MANISONE DOUANGBOUNPHENG ເລກບັນຊີ*</label>
    <div class="input-group">
        <input class="form-control" id="bank-number" value="98683912" readonly>
        <button class="btn btn-outline-secondary" type="button" id="copy-button">ຄັດລອກ</button>
    </div>
    <small id="copy-feedback" class="text-success" style="display:none;">Copied!</small>
</div>

<script>
    document.getElementById('copy-button').addEventListener('click', function() {
        var copyText = document.getElementById('bank-number');

        // Copy the text inside the input field
        navigator.clipboard.writeText(copyText.value).then(function() {
            // Show feedback message on successful copy
            var feedback = document.getElementById('copy-feedback');
            feedback.style.display = 'inline';
            setTimeout(function() {
                feedback.style.display = 'none';
            }, 2000); // Hide after 2 seconds
        }).catch(function(error) {
            console.error('Error copying text: ', error);
        });
    });
</script>

                            
                            <div class="mb-3">
                                <label for="orderImage"  class="fw-bold">ແນບຮູບພາບການຊຳລະເງິນທັງໝົດ*</label>
                                <input type="file" class="form-control" name="orderImage">
                            </div>

                            <input type="hidden" name="payment_mode" value="transfer">
                            <button type="submit" name="placeOrderBtn" class="btn btn-primary">ກົດສັ່ງຊື້</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="py-5 text-center"><h3>Your Cart is Empty</h3></div>
    <?php endif; ?>
</div>



<?php include('includes/footer.php'); ?>
