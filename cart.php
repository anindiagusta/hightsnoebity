<?php
session_start();

// hak akses hanya untuk customer
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 Not Found</h1>";
    exit;
}

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['product'])) { // cek apakah ada action delete dan product
    $productToDelete = $_GET['product']; // ambil nama produk dari parameter
    if (isset($_SESSION['cart'][$productToDelete])) { // cek apakah produk ada di cart
        unset($_SESSION['cart'][$productToDelete]); // hapus produk dari cart
        header('Location: ' . strtok($_SERVER["REQUEST_URI"], '?')); // redirect ke halaman yang sama tanpa parameter
        
        exit;
    }
}

// Initialize grand total
$grandTotal = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>Fashion Web</title>
  <style>
    body {
      font-family: 'Open Sans', sans-serif;
      margin: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
    }
    th {
      background-color: #333;
      color: white;
    }
    .total {
      font-weight: bold;
      text-align: right;
      padding-right: 10px;
    }
    .empty-cart {
      font-style: italic;
      color: #888;
      margin-bottom: 15px;
    }
    .button{
      background-color: #333;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 8px;
      display: inline-block;
    }
    .button:hover{
      background-color: #555;
    }
    .delete-button {
      padding: 5px 10px;
      font-size: 0.9em;
      border-radius: 5px;
    }
    .delete-button:hover {
      color: #e74c3c;
    }
  </style>
  <link href="css/FASHION.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet"> 
</head>

<body>
  <?php include 'header.php'; ?>

  <section>
    <h2>Your Shopping Cart</h2>
    <?php if (!empty($_SESSION['cart'])): ?>
      <form method="POST" action="checkout_form.php">
        <table>
          <thead>
            <tr>
              <th>Product Name</th>
              <th>Price (Rp)</th>
              <th>Quantity</th>
              <th>Subtotal (Rp)</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($_SESSION['cart'] as $name => $item): 
              $subtotal = $item['price'] * $item['quantity'];
              $grandTotal += $subtotal;
            ?>
            <tr>
              <td><?= htmlspecialchars($name) ?></td>
              <td><?= number_format($item['price'], 0, ',', '.') ?></td>
              <td><?= $item['quantity'] ?></td>
              <td><?= number_format($subtotal, 0, ',', '.') ?></td>
              <td>
                <a class="delete-button" href="?action=delete&product=<?= urlencode($name) ?>" 
                  onclick="return confirm('Are you sure to remove this item?');" 
                  title="Delete Item">
                  <i class='bx bx-trash'></i>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <div class="total-section" style="text-align:right; margin-top: 30px;">
          <div class="total" style="font-size: 1.2rem; font-weight: bold; margin-bottom: 10px;">
            Grand Total: Rp <?= number_format($grandTotal, 0, ',', '.') ?>
          </div>
          <input type="hidden" name="grand_total" value="<?= $grandTotal ?>">
          <button type="submit" class="button" onclick="return confirm('Checkout now?')" style="padding: 10px 20px; font-size: 1rem; cursor: pointer;">
            Checkout
          </button>
        </div>
      </form>
      <a href="products.php" class="button">Continue Shopping</a>
    <?php else: ?>
      <p class="empty-cart">Your cart is empty.</p>
      <a href="products.php" class="button">Shop Now</a>
    <?php endif; ?>
  </section>
</body>
</html>