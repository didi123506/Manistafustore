<?php 
session_start();
include('../middleware/adminMiddleware.php');
include('includes/header.php');
?>

<div class="container">
<div class="row">
       <div class="col-md-12">
           <div class="card">
               <div class="card-header">
                   <h4>Products</h4>
               </div>
               <div class="card-body">
                   <table class="table table-bordered table-striped">
                       <thead>
                           <tr>
                               <th>ID</th>
                               <th>Name</th>
                               <th>Image</th>
                               <th>Status</th>
                               <th>Edit</th>
                               <th>Delete</th>
                           </tr>
                       </thead>
                       <tbody>
                           <?php
                           $products = getAll("products");

                           if (mysqli_num_rows($products) > 0) {
                               foreach ($products as $item) {
                                   ?>
                                   <tr>
                                       <td><?= $item['id']; ?></td>
                                       <td><?= $item['name']; ?></td>
                                       <td>
                                           <img src="../uploads/<?= $item['image']; ?>" width="50px" height="50px" alt="<?= $item['name']; ?>">
                                       </td>
                                       <td>
                                           <?= $item['status'] == '0' ? "Visible" : "Hidden" ?>
                                       </td>
                                       <td>
                                           <a href="edit-products.php?id=<?= $item['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                       </td>
                                       <td>
                                           <a href="javascript:void(0)" class="btn btn-sm btn-danger delete-product-btn" data-id="<?= $item['id']; ?>">Delete</a>
                                       </td>
                                   </tr>
                                   <?php
                               }
                           } else {
                               echo "No record found";
                           }
                           ?>
                       </tbody>
                   </table>

                   <!-- Script to le delete confirmation -->
                   <script>
                       document.querySelectorAll('.delete-product-btn').forEach(button => {
                           button.addEventListener('click', function() {
                               const productId = this.getAttribute('data-id');
                               const confirmDelete = confirm('Are you sure you want to delete this product?');
                               if (confirmDelete) {
                                   // Create a form to submit delete request
                                   let form = document.createElement('form');
                                   form.method = 'POST';
                                   form.action = 'code.php';

                                   let inputProductId = document.createElement('input');
                                   inputProductId.type = 'hidden';
                                   inputProductId.name = 'product_id';
                                   inputProductId.value = productId;

                                   let inputDeleteBtn = document.createElement('input');
                                   inputDeleteBtn.type = 'hidden';
                                   inputDeleteBtn.name = 'delete_product_btn';

                                   form.appendChild(inputProductId);
                                   form.appendChild(inputDeleteBtn);
                                   document.body.appendChild(form);

                                   form.submit();
                               }
                           });
                       });
                   </script>

               </div>
           </div>
       </div>
</div>
</div>

<?php include('includes/footer.php'); ?>