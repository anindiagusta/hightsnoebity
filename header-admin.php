<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>HIGHTSNOEBITY</title>
  <link rel="stylesheet" href="css/FASHION.css">
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
<header>
  <a href="#" class="logo">HIGHTSNOEBITY</a>
  <ul class="navlist">
    <a href="home-admin.php" class="<?= $currentPage == 'home-admin.php' ? 'active' : '' ?>">Home</a>
    <a href="products-crud.php" class="<?= $currentPage == 'products-crud.php' ? 'active' : '' ?>">Product</a>
    <a href="brand-category-crud.php" class="<?= $currentPage == 'brand-category-crud.php' ? 'active' : '' ?>">Brands & Categories</a>
    <a href="orders.php" class="<?= $currentPage == 'orders.php' ? 'active' : '' ?>">Orders</a>
  </ul>
  <div class="header-icons">
    <?php if (isset($_SESSION['user'])): ?>
      <div class="profile-popup">
        <input type="checkbox" id="toggle-popup" />
        <label for="toggle-popup" title="Profile">
          <i class='bx bxs-user'></i>
        </label>
        <div class="popup-content">
          <a href="profile.php">Profile</a>
          <a href="logout.php">Logout</a>
        </div>
      </div>
    <?php else: ?>
      <a href="login.php" class="login-button">Login</a>
      <a href="register.php" class="login-button">Register</a>
    <?php endif; ?>

    <div class="bx bx-menu" id="menu-icon"></div>
  </div>

  <div id="login-alert" class="popup-overlay">
    <div class="popup-box">
      <p>You must login first to add to cart.</p>
      <a href="login.php" class="popup-btn">Login Now</a>
      <button onclick="closePopup()" class="popup-close">&times;</button>
    </div>
  </div>
</header>