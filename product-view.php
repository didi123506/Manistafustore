<?php
session_start();
include('functions/userfunctions.php');
include('includes/header.php');

if (isset($_GET['product'])) {
   // Get the product slug from the URL
   $product_slug = $_GET['product'];
   
   // Fetch product data using the slug
   $product_data = getSlugActive("products", $product_slug);
   $product = mysqli_fetch_array($product_data);

   // Check if the product exists
   if($product) {
       ?>
       <div class="py-3" style="background-color:#a389dd ; color: white">
           <div class="container">
               <h6 class="text-white"> 
                   <a class="text-white" href="categories.php">Home /</a>
                   <a class="text-white" href="categories.php">Collections /</a>
                   <?= $product['name']; ?>
               </h6>
           </div>
       </div>

       <div class="py-3">
           <div class="container product_data">
               <div class="row">
                   <div class="col-md-5">
                       <img src="uploads/<?= $product['image']; ?>" alt="Product Image" class="w-100">
                   </div>
                   <div class="col-md-7">
                       <h4><?= $product['name']; ?></h4>
                       <hr>
                       <p><?= $product['small_description']; ?></p>
                       <h5>Price: ₭<?= number_format($product['selling_price'],0,',',','); ?> 
                           <small class="text-muted">
                               <s>₭<?= $product['original_price']; ?></s>
                           </small>
                           </h5>
                      
                       <div class="row">
                            <div class="col-md-3">
                                <div class="input-group mb-3" style="width:130px">
                                    <button class="input-group-text decrement-btn">-</button>
                                    <input type="text" class="form-control text-center input-qty bg-white" value="1" disabled>
                                    <button class="input-group-text increment-btn">+</button>
                                </div>
                            </div>
                            
                            <div class="row my-3">
                               <div class="col-md-6">
                                   <button class="btn px-4 addTocartBtn" value="<?= $product['id']; ?>" style="background-color:#a389dd; color:white;">
                                       <i class="fas fa-shopping-cart"></i> Add to Cart
                                   </button>                 
                               </div>
                               <div class="col-md-6">
                                   <button class="btn "  style="background-color:#d85959; color:white;">
                                       <i class="fas fa-heart"></i> Add to Wishlist
                                   </button>
                               </div>        
                           </div>

                       <hr>
                       <h6>Product Description:</h6>
                       <p><?= $product['description']; ?></p>
                   </div>
               </div>
           </div>
       </div>
       <?php
   } else {
       // Redirect to categories if product is not found
       header("Location: categories.php");
       exit();
   }
} else {
   // Redirect to categories if product slug is missing
   header("Location: categories.php");
   exit();
}

include('includes/footer.php');
?>