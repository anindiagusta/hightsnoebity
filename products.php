<?php
session_start();
include 'config.php';

// Ambil filter dari URL (default: ALL)
$filter = isset($_GET['filter']) ? strtoupper($_GET['filter']) : 'ALL';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 16;
$offset = ($page - 1) * $limit;

// Tentukan kondisi WHERE dan judul
$where = "";
$judul = "ALL PRODUCTS";

if ($filter === 'MEN') {
    $where = "WHERE UPPER(gender) = 'MEN'";
    $judul = "MEN DISPLAY";
} elseif ($filter === 'GIRLY') {
    $where = "WHERE UPPER(gender) = 'GIRLY'";
    $judul = "GIRLY DISPLAY";
}

// Hitung total produk
$totalQuery = "SELECT COUNT(*) as total FROM products $where";
$totalResult = $conn->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$totalProducts = $totalRow['total'];
$totalPages = ceil($totalProducts / $limit);

// Ambil produk sesuai halaman
$sql = "SELECT id, name, image_path, gender, harga, discount_price FROM products $where LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Fashion Web</title>
  <link href="css/FASHION.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300..800&display=swap" rel="stylesheet" />
</head>

<body>
    <?php include 'header.php'; ?>

  <section class="Product" id="Product">

    <div class="filter-buttons">
      <a href="products.php?filter=men" class="man-woman-button <?= $filter === 'MEN' ? 'active' : '' ?>">MEN</a>
      <a href="products.php?filter=girly" class="man-woman-button <?= $filter === 'GIRLY' ? 'active' : '' ?>">GIRLY</a>
    </div>

    <div class="center-text">
      <h2><?= $judul ?></h2>
    </div>

    <div class="Product-content">
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
          <a href="product_detail.php?id=<?= $row['id'] ?>" style="text-decoration: none; color: inherit;">
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
          </a>

          <?php if (isset($_SESSION['user'])): ?>
              <!-- Jika sudah login, submit ke add_to_cart.php -->
              <form method="POST" action="add_to_cart.php">
                  <input type="hidden" name="product_name" value="<?= htmlspecialchars($row['name']) ?>">
                  <input type="hidden" name="product_price" value="<?= $hargaDiskon ?>">
                  <button type="submit" class="add-chart-btn">Add to Cart</button>
              </form>
          <?php else: ?>
              <!-- Jika belum login, tombol akan memunculkan popup -->
              <button class="add-chart-btn" onclick="showLoginAlert()">Add to Cart</button>
          <?php endif; ?>
      </div>
    <?php endwhile; ?>
    </div>

    <div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?filter=<?= strtolower($filter) ?>&page=<?= $page - 1 ?>" class="pagination-button">« Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?filter=<?= strtolower($filter) ?>&page=<?= $i ?>" class="pagination-button <?= $i == $page ? 'active' : '' ?>">
        <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a href="?filter=<?= strtolower($filter) ?>&page=<?= $page + 1 ?>" class="pagination-button">Next »</a>
    <?php endif; ?>
    </div>
  </section>

  <div id="login-alert" class="popup-overlay">
    <div class="popup-box">
      <p>You must login first to add to cart.</p>
      <a href="login.php" class="popup-btn">Login Now</a>
      <button onclick="closePopup()" class="popup-close">&times;</button>
    </div>
  </div>

  <a href="#" class="top"><i class='bx bx-up-arrow-alt'></i></a>

  <?php include 'footer.php'; ?>

  <script src="js/script.js"></script>
</body>
</html>