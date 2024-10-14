<?php
session_start();
include('../config/dbcon.php');
include('../functions/myfunctions.php');

if(isset($_POST['add_category_btn']))
{
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $description = $_POST['description'];
    $meta_title = $_POST['meta_title'];
    $meta_description = $_POST['meta_description'];
    $meta_keywords = $_POST['meta_keywords'];
    $status = isset($_POST['status']) ? '1': '0' ;
    $popular = isset($_POST['popular']) ? '1': '0' ;

    $image = $_FILES['image']['name'];
    $path = "../uploads";

    $image_ext = pathinfo($image, PATHINFO_EXTENSION);
    $filename = time().'.'.$image_ext;

      $cate_query = "INSERT iNTO categories
      (name,slug,description,meta_title,meta_description,meta_keywords,status,popular,image) 
      VALUES('$name','$slug','$description','$meta_title','$meta_description','$meta_keywords','$status','$popular','$filename')";
  
  $cate_query_run = mysqli_query($con, $cate_query);

  if($cate_query_run)
  {
    move_uploaded_file($_FILES['image']['tmp_name'], $path.'/'.$filename);

    redirect("add-category.php", "Category added Success");
  }
  else
  {
   redirect("add-category.php", "Something went wrong");
  }

}
else if(isset($_POST['update_category_btn']))
{
  $category_id = $_POST['category_id'];
  $name = $_POST['name'];
  $slug = $_POST['slug'];
  $description = $_POST['description'];
  $meta_title = $_POST['meta_title'];
  $meta_description = $_POST['meta_description'];
  $meta_keywords = $_POST['meta_keywords'];
  $status = isset($_POST['status']) ? '1': '0' ;
  $popular = isset($_POST['popular']) ? '1': '0' ;

  $new_image = $_FILES['image']['name'];
  $old_image = $_POST['old_image'];

  $update_filename = $new_image != "" ? time() . '.' . pathinfo($new_image, PATHINFO_EXTENSION) : $old_image;
  $path = "../uploads";

  $update_query = "UPDATE categories SET name='$name', slug='$slug', description='$description' ,
   meta_title='$meta_title', meta_description='$meta_description', meta_keywords='$meta_keywords',
   status='$status', popular='$popular', image='$update_filename' WHERE id='$category_id' ";
  
   $update_query_run = mysqli_query($con, $update_query);

   if($update_query_run)
   {
    if ($new_image != "") {
      move_uploaded_file($_FILES['image']['tmp_name'], $path . '/' . $update_filename);
      if (file_exists("../uploads/" . $old_image)) {
          unlink("../uploads/" . $old_image);
      }
  }
  redirect("edit-category.php?id=$category_id", "Category updated successfully");
} else {
  redirect("edit-category.php?id=$category_id", "Failed to update category");
}
}
else if(isset($_POST['delete_category_btn']))
{
  $category_id = mysqli_real_escape_string($con,$_POST['category_id']);

  $category_query = "SELECT * FROM categories WHERE id='$category_id' ";
  $cate_query_run = mysqli_query($con, $category_query);
  $category_data = mysqli_fetch_array($cate_query_run);
  $image = $category_data['image'];

  $delete_query = "DELETE FROM categories WHERE id='$category_id' ";
  $delete_query_run = mysqli_query($con, $delete_query);

  if($delete_query_run)
  {

    if (file_exists("../uploads/" . $image)) 
    {
      unlink("../uploads/" . $image);
    }
      redirect("category.php", "Category deleted Successfully");
  }
  else{
    redirect("category.php", "Something wrong");
  }
}

else if (isset($_POST['add_products_btn']))
{
  $category_id = $_POST['category_id'];
  $name = $_POST['name'];
  $slug = $_POST['slug'];
  $small_description = $_POST['small_description'];
  $description = $_POST['description'];
  $original_price = $_POST['original_price'];
  $selling_price = $_POST['selling_price'];
  $qty = $_POST['qty'];
  $meta_title = $_POST['meta_title'];
  $meta_description = $_POST['meta_description'];
  $meta_keywords = $_POST['meta_keywords'];
  $status = isset($_POST['status']) ? '1': '0' ;
  $trending = isset($_POST['trending']) ? '1': '0' ;

  $image = $_FILES['image']['name'];
  $path = "../uploads";

  $image_ext = pathinfo($image, PATHINFO_EXTENSION);
  $filename = time().'.'.$image_ext;

  if($name !="" && $slug != "" && $description != "")
  {


   $product_query = "INSERT INTO products (category_id,name,slug,small_description,description,original_price,selling_price,qty,
   meta_title,meta_description,meta_keywords,status,trending,image) VALUES
   ('$category_id','$name','$slug','$small_description',' $description','$original_price','$selling_price','$qty','$meta_title','$meta_description',
   '$meta_keywords','$status','$trending','$filename') ";

   $product_query_run = mysqli_query($con,$product_query);

   if($product_query_run)
   {
    move_uploaded_file($_FILES['image']['tmp_name'], $path.'/'.$filename);

    redirect("add-products.php", "Product added Success");
   }
   else
   {
    redirect("add-products.php", "Something went wrong");
   }
}
else
   {
    redirect("add-products.php", "All fields are mandatory");
   }

}
else if(isset($_POST['update_products_btn']))
{
  $product_id = $_POST['product_id'];
  $category_id = $_POST['category_id'];
  $name = $_POST['name'];
  $slug = $_POST['slug'];
  $small_description = $_POST['small_description'];
  $description = $_POST['description'];
  $original_price = $_POST['original_price'];
  $selling_price = $_POST['selling_price'];
  $qty = $_POST['qty'];
  $meta_title = $_POST['meta_title'];
  $meta_description = $_POST['meta_description'];
  $meta_keywords = $_POST['meta_keywords'];
  $status = isset($_POST['status']) ? '1': '0' ;
  $trending = isset($_POST['trending']) ? '1': '0' ;

  $path = "../uploads";

  $new_image = $_FILES['image']['name'];
  $old_image = $_POST['old_image'];
  $update_filename = $new_image != "" ? time() . '.' . pathinfo($new_image, PATHINFO_EXTENSION) : $old_image;


  $update_product_query = "UPDATE products SET category_id='$category_id',name='$name', slug='$slug',small_description='$small_description', description='$description' ,
  meta_title='$meta_title', meta_description='$meta_description', meta_keywords='$meta_keywords',
  status='$status', trending='$trending', image='$update_filename' WHERE id='$product_id' ";

  $update_product_query_run = mysqli_query($con,$update_product_query);

  if($update_product_query_run)
  {
   if ($new_image != "") {
     move_uploaded_file($_FILES['image']['tmp_name'], $path . '/' . $update_filename);
     
     if (file_exists("../uploads/" . $old_image)) {
         unlink("../uploads/" . $old_image);
     }
 }
 redirect("edit-products.php?id=$product_id", "products updated successfully");
} else {
  echo mysqli_error($con);
 redirect("edit-products.php?id=$product_id", "Failed to update products");
}
  
}

else if(isset($_POST['delete_product_btn'])) 
{
    $product_id = mysqli_real_escape_string($con,$_POST['product_id']);

    // Get product details
    $product_query = "SELECT * FROM products WHERE id='$product_id' ";
    $product_query_run = mysqli_query($con, $product_query);

    if(mysqli_num_rows($product_query_run) > 0) 
    {
        $product_data = mysqli_fetch_array($product_query_run);
        $image = $product_data['image'];

        // Delete product from database
        $product_delete_query = "DELETE FROM products WHERE id='$product_id' ";
        $product_delete_query_run = mysqli_query($con, $product_delete_query);

        if($product_delete_query_run) 
        {
            // Delete image from the server if it exists
            if (file_exists("../uploads/" . $image)) 
            {
                unlink("../uploads/" . $image);
            }
            redirect("products.php", "Product deleted Successfully");
        } 
        else 
        {
            redirect("products.php", "Failed to delete product");
        }
    }
    else 
    {
        redirect("products.php", "Product not found");
    }
}
 
?>