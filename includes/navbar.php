

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
  <a class="navbar-brand"  href="index.php" >  MANISTAFU</a> 
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">ໜ້າຫຼັກ</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="categories.php">ໝວດໝູ່</a>
        </li>
   
        <li class="nav-item">
          <a class="nav-link" href="cart.php">ກະຕ່າ</a>
        </li>
        <?php
       
            if(isset($_SESSION['auth']))
            {
              ?>
     <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <?= $_SESSION['auth_user']['name'] ?>
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="my-orders.php">ຄຳສັ່ງຊື້ທັງໝົດ</a></li>
    
            <li><a class="dropdown-item" href="logout.php">ອອກຈາກລະບົບ</a></li>
          </ul>
        </li>
              <?php
            }
            else 
            {
?>
<li class="nav-item">
          <a class="nav-link" href="register.php">Register</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="login.php">Login</a>
        </li>

<?php
            }
        
?>
        
        
      </ul>
    </div>
  </div>
</nav>