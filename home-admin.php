<?php
session_start();

// hak akses hanya untuk admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 Not Found</h1>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Fashion Web</title>

  <link href="css/FASHION.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet"/>
</head>

<body>
  
  <!-- include header -->
  <?php include 'header-admin.php'; ?>

  <section class="home" id="home" style="background-image: url('images/views/home.png');">
    <div class="home-text">
      <h1>HIGHT <br><span>SNOEBITY</span></h1>
      <p>New colors, now also available in Fashion sizing</p>
      <a href="view.html" class="btn"> About Us</a>
    </div>
  </section>
  <script src="js/script.js"></script>
</body>
</html>