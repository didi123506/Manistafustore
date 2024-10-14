<?php
session_start();
include('functions/userfunctions.php');
include('includes/header.php');
?>

<div class="py-2" style="background-color:#a389dd ; color: white">
<div class="container">
<h6><a class="text-white" href="index.php">ໜ້າຫຼັກ </a> /
            <a class="text-white" href="categories.php">ກະຕ່າ</a>
        </h6>
</div>
</div>

<div class="py-5"> 
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>ທຸກໝວດໝູ່></h1>
            <hr>
            <div class="row">
                <?php
                        
            $categories = getAllActive("categories");

            if(mysqli_num_rows($categories)>0)
            {
                foreach($categories as $item)
                {
                    ?>
                    <div class="col-md-3 mb-2">
                        <a href="product.php?category=<?= $item['slug'];?>">
                        <div class="card shadow">
                            <div class="card-body">
                            <img src="uploads/<?= $item['image'];?>" alt="Category Image" class="w-100">
                            <h4 class="text-center"><?= $item['name'];?></h4>
                            </div>
                        </div>
                        </a>
                    </div>
                    <?php
                }
            }
            else
            {
                echo"No data available";
            }
           ?>
</div>
</div>
</div>
</div>
</div>
 
   <?php include('includes/footer.php'); ?>