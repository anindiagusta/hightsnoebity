<?php
include 'config.php';
session_start();

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT p.*, b.name as brand_name
        FROM products p
        JOIN brands b ON p.brand_id = b.id
        WHERE p.id = $productId";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Product not found.";
    exit;
}

$product = $result->fetch_assoc();
$diskonPersen = (int)$product['discount_price'];
$hargaAsli = (int)$product['harga'];
$hargaDiskon = $hargaAsli;

if ($diskonPersen > 0) {
    $hargaDiskon = $hargaAsli - ($hargaAsli * $diskonPersen / 100);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($product['name']) ?> - Detail</title>
  <link rel="stylesheet" href="css/product.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="product-detail">
  <div class="container">
    <div class="detail-card">
      <div class="detail-images">
        <!-- Main Image -->
        <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="main-image" id="mainImage">
      </div>

      <div class="detail-info">
        <h2><?= htmlspecialchars($product['name']) ?></h2>
        <table class="product-info-table">
        <tr>
            <td><strong>Brand:</strong></td>
            <td><?= htmlspecialchars($product['brand_name']) ?></td>
        </tr>
        <tr>
            <td><strong>Gender:</strong></td>
            <td><?= htmlspecialchars($product['gender']) ?></td>
        </tr>
        <tr>
            <td><strong>Size:</strong></td>
            <td><?= htmlspecialchars($product['size']) ?></td>
        </tr>
        <tr>
            <td><strong>Stock:</strong></td>
            <td><?= $product['stock'] ?></td>
        </tr>
        <tr>
            <td style="vertical-align: top;"><strong>Description:</strong></td>
            <td><?= nl2br(htmlspecialchars($product['description'])) ?></td>
        </tr>
        </table>
        
        <p class="price">
          Rp <?= number_format($hargaDiskon, 0, ',', '.') ?>
          <?php if ($diskonPersen > 0): ?>
            <small class="harga-asli">Rp <?= number_format($hargaAsli, 0, ',', '.') ?></small>
          <?php endif; ?>
        </p>

        <div class="add-to-cart-section">
          <?php if (isset($_SESSION['user'])): ?>
            <form method="POST" action="add_to_cart.php" style="display:flex; align-items:center; gap:10px;">
              <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
              <input type="hidden" name="product_price" value="<?= $hargaDiskon ?>">
              <input type="number" name="quantity" min="1" max="<?= $product['stock'] ?>" value="1" class="quantity-input" required>
              <button type="submit" class="add-chart-btn">Add to Cart</button>
            </form>
          <?php else: ?>
            <button class="add-chart-btn" onclick="showLoginAlert()">Add to Cart</button>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include 'footer.php'; ?>

</body>
</html>
