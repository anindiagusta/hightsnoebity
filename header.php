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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HIGHTSNOEBITY</title>
  <link rel="stylesheet" href="css/FASHION.css">
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
<header>
  <a href="#" class="logo">HIGHTSNOEBITY</a>
  <ul class="navlist">
    <a href="home.php" class="<?= $currentPage == 'home.php' ? 'active' : '' ?>">Home</a>
    <a href="products.php" class="<?= $currentPage == 'products.php' ? 'active' : '' ?>">Product</a>
    <a href="sale.php" class="<?= $currentPage == 'sale.php' ? 'active' : '' ?>">Sale</a>
    <a href="contact-us.php" class="<?= $currentPage == 'contact-us.php' ? 'active' : '' ?>">Contact Us</a>
  </ul>
  <div class="header-icons">
    <?php if (isset($_SESSION['user'])): ?>
      <!-- Sudah login, cart bisa diakses -->
      <a href="cart.php"><i class='bx bx-cart'></i></a>
    <?php else: ?>
      <!-- Belum login, klik icon cart munculkan popup -->
      <a href="javascript:void(0);" onclick="showLoginAlert()"><i class='bx bx-cart'></i></a>
    <?php endif; ?>
    
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

<script>
  const menuToggle = document.getElementById('menu-icon');
  const navlist = document.querySelector('.navlist');

  menuToggle.addEventListener('click', () => {
    navlist.classList.toggle('open');
    menuToggle.classList.toggle('active');

    // Ganti ikon (menu ↔ X)
    if (menuToggle.classList.contains('active')) {
      menuToggle.classList.replace('bx-menu', 'bx-x');
    } else {
      menuToggle.classList.replace('bx-x', 'bx-menu');
    }
  });
</script>