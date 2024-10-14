<?php

?>
<?php
include('config/dbcon.php');

function getAllActive($table)
{
  global $con;
  $query = "SELECT * FROM $table WHERE status='0' ";
  return $query_run = mysqli_query($con, $query);
}

function getSlugActive($table, $slug)
{
    global $con;
    $query = "SELECT * FROM $table WHERE slug= '$slug' AND status='0' LIMIT 1";
    return mysqli_query($con, $query);
}

function getProByCategory($category_id)
{
    global $con;
    // Correcting the query by using the actual variable $category_id
    $query = "SELECT * FROM products WHERE category_id= '$category_id' AND status='0'";
    return mysqli_query($con, $query);
}

function getIDActive($table, $id)
{
  global $con;
  $query = "SELECT * FROM $table WHERE id= '$id' AND status='0' ";
  return $query_run = mysqli_query($con, $query);
}


function getCartItems() {
  global $con;
  $userid = $_SESSION['auth_user']['user_id'];

  $query = "SELECT c.id as cid, c.prod_id, c.prod_qty, p.id as pid, p.name, p.image, p.selling_price 
            FROM carts c, products p WHERE c.prod_id = p.id AND  c.user_id = '$userid' ORDER BY c.id DESC";

return $query_run = mysqli_query($con, $query);

}


function getOrders()
{
    global $con;
    $userid = $_SESSION['auth_user']['user_id']; // Make sure this is correctly set

    $query = "SELECT * FROM orders WHERE user_id='$userid'";
  
    return mysqli_query($con, $query);  // No need to assign the return to another variable
}


function redirect($url, $message) 
{
  $_SESSION['message'] = $message;
  header('Location: '.$url); 
  exit(0);
}


?>
