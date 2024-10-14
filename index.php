<?php
session_start();
include('functions/userfunctions.php');
include('config/dbcon.php');
include('includes/header.php');

// Search functionality
$search_term = '';
if (isset($_GET['search'])) {
    $search_term = mysqli_real_escape_string($con, $_GET['search']);
}

$query = "SELECT * FROM products WHERE status='0'"; // Show only active products
if ($search_term != '') {
    $query .= " AND name LIKE '%$search_term%'"; // Append search condition if search term exists
}

$query_run = mysqli_query($con, $query);
?>

<!-- Main content of your index page -->
<div class="container"> <br>
    <h1>ຮ້ານ Manistafu </h1>
    <p>ເຄື່ອງຂຽນ , ເຄື່ອງຮຽນ ແລະ ອຸປະກອນແຕ້ມຮູບມີຫຼາກຫຼາຍແບບໃຫ້ທ່ານເລືອກໃຊ້</p>

    <!-- Search form -->
    <form action="index.php" method="GET" class="d-flex mb-4">
        <input class="form-control me-5" type="search" name="search" placeholder="ຊອກຫາສິນຄ້າ..." value="<?= htmlspecialchars($search_term); ?>">
        <button class="btn btn-outline-success" type="submit">Search</button>
    </form>

    <!-- Product display -->
    <div class="row">
        <?php if (mysqli_num_rows($query_run) > 0): ?>
            <?php while ($product = mysqli_fetch_assoc($query_run)): ?>
                <div class="col-md-3 mb-4 product_data">
                    <div class="card h-100">
                        <img src="uploads/<?= $product['image']; ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']); ?>" height="290px">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text"><?= number_format($product['selling_price'], 0, '.', ','); ?> LAK</p>

                            <!-- Display available quantity -->
                            <p class="card-text">ສິນຄ້າໃນຄັງ: <strong><?= $product['qty']; ?></strong></p>

                            <!-- Quantity selector with increment and decrement buttons -->
                            <div class="input-group mb-3">
                                <button class="btn btn-outline-secondary decrement-btn" type="button">-</button>
                                <input type="text" class="form-control text-center input-qty" value="1" min="1" max="<?= $product['qty']; ?>" readonly>
                                <button class="btn btn-outline-secondary increment-btn" type="button">+</button>
                            </div>

                            <!-- Add to Cart Button -->
                            <button class="btn px-1 addTocartBtn" value="<?= $product['id']; ?>" style="background-color:#a389dd; color:white;">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-center">ບໍ່ມີສິນຄ້າໃນລາຍການ.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Footer Section -->
 <br><br><br><br><br><br><br><br><br><br><br><br><br>
<footer class="bg-dark text-white mt-5">
    <div class="container-fluid py-2">
        <div class="row">
            <div class="col-md-4">
                <h5>About Us</h5>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.</p>
            </div>
            <div class="col-md-4">
                <h5>Address</h5>
                <p>1234 Street Name, City, Country</p>
            </div>
            <div class="col-md-4">
                <h5>Store Data</h5>
                <p>Open: 9:00 AM - 5:00 PM (Mon - Fri)</p>
                <p>Contact: (123) 456-7890</p>
            </div>
        </div>
    </div>
    <div class="text-center py-2" style="background-color:#a389dd ; color: white" >
       MANISTAFU SHOP
    </div>
</footer>

<?php
include('includes/footer.php');
?>

<!-- Custom Script for Add to Cart and Quantity handling -->
<script><script>
$(document).ready(function () {
    // Ensure each button click only triggers one event handler
    $('.increment-btn').off('click').on('click', function () {
        var qtyInput = $(this).closest('.input-group').find('.input-qty');
        var currentQty = parseInt(qtyInput.val());
        var maxQty = parseInt(qtyInput.attr('max')); // Get the maximum available quantity

        // Only increase the quantity if it's less than maxQty
        if (currentQty < maxQty) {
            qtyInput.val(currentQty + 1); // Increase the quantity
        }

        // Disable increment button if the quantity reaches the maximum
        if (parseInt(qtyInput.val()) >= maxQty) {
            $(this).prop('disabled', true); // Disable increment button
        }

        // Enable the decrement button (if previously disabled)
        $(this).closest('.input-group').find('.decrement-btn').prop('disabled', false);
    });

    // Decrement quantity
    $('.decrement-btn').off('click').on('click', function () {
        var qtyInput = $(this).closest('.input-group').find('.input-qty');
        var currentQty = parseInt(qtyInput.val());

        if (currentQty > 1) {
            qtyInput.val(currentQty - 1); // Decrease the quantity
        }

        // Enable the increment button when decrementing
        $(this).closest('.input-group').find('.increment-btn').prop('disabled', false);

        // Disable the decrement button if the current quantity reaches 1
        if (parseInt(qtyInput.val()) <= 1) {
            $(this).prop('disabled', true); // Disable decrement button
        }
    });

    // Initially disable decrement button if quantity is 1
    $('.input-qty').each(function() {
        if ($(this).val() == 1) {
            $(this).closest('.input-group').find('.decrement-btn').prop('disabled', true);
        }
    });
});
</script>




