<?php
session_start();
include 'config.php';

// Ambil produk yang memiliki diskon (discount_price > 0)
$sql = "SELECT name, image_path, harga, discount_price FROM products WHERE discount_price > 0";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>Fashion Web</title>
  <link href="css/FASHION.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet"> 
</head>

<body>
  <?php include 'header.php'; ?>

  <section class="cta" style="background-image: url('images/views/background-sale.png');">
    <div class="cta-text">
      <h6>SUMMER ON SALE</h6>
      <h4>70% OFF <br> NEW ARRIVAL</h4>
      <a href="products.php" class="btn">Shop Now</a>
    </div>
  </section>

  <section class="New" id="New">
    <div class="center-text">
      <h2>New Arrivals</h2>
    </div>

    <section class="Product-content">
      <?php while ($row = $result->fetch_assoc()): ?>
          <?php
          $diskonPersen = (int)$row['discount_price'];
          $hargaAsli = (int)$row['harga'];
          $hargaDiskon = $hargaAsli;

          if ($diskonPersen > 0) {
              $hargaDiskon = $hargaAsli - ($hargaAsli * $diskonPersen / 100);
          }
          ?>
          <div class="product-card">
              <?php if ($diskonPersen > 0): ?>
                  <div class="sale-badge">SALE <?= $diskonPersen ?>%</div>
              <?php endif; ?>

              <img src="<?= htmlspecialchars($row['image_path']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
              <div class="fea-text">
                  <h5><?= htmlspecialchars($row['name']) ?></h5>
                  <p>
                      Rp <?= number_format($hargaDiskon, 0, ',', '.') ?><br>
                      <?php if ($diskonPersen > 0): ?>
                          <small class="harga-asli">Rp <?= number_format($hargaAsli, 0, ',', '.') ?></small>
                      <?php endif; ?>
                  </p>
              </div>

              <?php if (isset($_SESSION['user'])): ?>
                <form method="POST" action="add_to_cart.php">
                  <input type="hidden" name="product_name" value="<?= htmlspecialchars($row['name']) ?>">
                  <input type="hidden" name="product_price" value="<?= $hargaDiskon ?>">
                  <button type="submit" class="add-chart-btn">Add to Cart</button>
                </form>
              <?php else: ?>
                <button class="add-chart-btn" onclick="showLoginAlert()">Add to Cart</button>
              <?php endif; ?>
          </div>
      <?php endwhile; ?>
    </section>

  <div class="pagination">
      <?php if ($page > 1): ?>
          <a href="?filter=<?= htmlspecialchars($filter) ?>&page=<?= $page - 1 ?>" class="pagination-button">« Previous</a>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <a href="?filter=<?= htmlspecialchars($filter) ?>&page=<?= $i ?>" class="pagination-button <?= $i == $page ? 'active' : '' ?>">
          <?= $i ?>
          </a>
      <?php endfor; ?>

      <?php if ($page < $totalPages): ?>
          <a href="?filter=<?= htmlspecialchars($filter) ?>&page=<?= $page + 1 ?>" class="pagination-button">Next »</a>
      <?php endif; ?>
  </div>

  <a href="#" class="top"><i class='bx bx-up-arrow-alt'></i></a>
</body>
</html>
<?php include 'footer.php'; ?>
