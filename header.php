<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<header>
  <a href="#" class="logo">HIGHTSNOEBITY</a>
  <ul class="navlist">
    <a href="home.php">Home</a>
    <a href="products.php">Product</a>
    <a href="sale.php">Sale</a>
    <a href="contact-us.php">Contact Us</a>
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
