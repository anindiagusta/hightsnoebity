<?php
session_start();

// Cek login dan cart
if (!isset($_SESSION['user']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Include database configuration
include 'config.php';

$cart = $_SESSION['cart']; // Format: [product_name => ['price' => ..., 'quantity' => ...]]

$products = []; // Array untuk menyimpan detail produk
$grandTotal = 0; // Inisialisasi total belanja

foreach ($cart as $name => $item) { // Looping setiap item di cart
    $price = (float)$item['price'];
    $qty = (int)$item['quantity'];
    $subtotal = $price * $qty;

    $products[] = [  // Simpan detail produk ke array
        'name' => $name,
        'price' => $price,
        'qty' => $qty,
        'total' => $subtotal
    ];

    $grandTotal += $subtotal; // Tambahkan subtotal ke grand total
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Checkout</title>
  <link rel="stylesheet" href="css/product.css"/>
  <link rel="stylesheet" href="css/cart.css"/>
</head>
<body>

    <?php include 'header.php'; ?>

  <h2 class="title-center">Checkout</h2>

  <div class="checkout-wrapper">
    <!-- Form Checkout -->
    <div class="checkout-form">
      <form method="POST" action="checkout_process.php">
        <label for="address">Shipping Address</label>
        <textarea name="address" id="address" required rows="4" placeholder="Enter your shipping address"></textarea>

        <label for="payment_method">Payment Method</label>
        <select name="payment_method" id="payment_method" required>
          <option value="">-- Select Payment Method --</option>
          <option value="COD">Cash on Delivery</option>
          <option value="Transfer">Bank Transfer</option>
          <option value="E-Wallet">E-Wallet</option>
        </select>

        <input type="hidden" name="grand_total" value="<?= $grandTotal ?>">

        <button type="submit" class="btn-submit">Confirm & Place Order</button>
      </form>
    </div>

    <!-- Ringkasan Order -->
    <div class="order-summary">
      <h3>Order Details</h3>
      <table class="order-table">
        <thead>
          <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $item): ?>
          <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= $item['qty'] ?></td>
            <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
            <td>Rp <?= number_format($item['total'], 0, ',', '.') ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" style="text-align:right;"><strong>Grand Total</strong></td>
            <td><strong>Rp <?= number_format($grandTotal, 0, ',', '.') ?></strong></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

</body>
</html>
