<?php
session_start();
include('functions/userfunctions.php');
include('includes/header.php');

if (isset($_GET['category'])) {
    $category_slug = $_GET['category'];
    $category_data = getSlugActive("categories", $category_slug);
    $category = mysqli_fetch_array($category_data);

    if ($category) {
        $cid = $category['id'];
        ?>

        <!-- Breadcrumb and Category Name -->
        <div class="py-3" style="background-color:#a389dd ; color: white">
            <div class="container">
                <h6 class="text-white"> 
                    <a class="text-white" href="categories.php">Home /</a>
                    <a class="text-white" href="categories.php">Collections /</a>
                    <?= $category['name']; ?>
                </h6>
            </div>
        </div>

        <!-- Category Product Display -->
        <div class="py-3"> 
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1><?= $category['name']; ?></h1>
                        <hr>
                        <div class="row">
                            <?php
                            $products = getProByCategory($cid);

                            if (mysqli_num_rows($products) > 0) {
                                foreach ($products as $item) {
                                    ?>
                                    <div class="col-md-3 mb-2">
                                        <a href="product-view.php?product=<?= $item['slug']; ?>">
                                            <div class="card shadow">
                                                <div class="card-body">
                                                    <img src="uploads/<?= $item['image']; ?>" alt="<?= $item['name']; ?>" class="w-100">
                                                    <h4 class="text-center"><?= $item['name']; ?></h4>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="col-md-12">
                                    <h4>No products available in this category</h4>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
    } else {
        // Redirect to categories page if category not found
        header("Location: categories.php");
        exit();
    }
} else {
    // Redirect if category parameter is missing
    header("Location: categories.php");
    exit();
}

include('includes/footer.php'); 
?>