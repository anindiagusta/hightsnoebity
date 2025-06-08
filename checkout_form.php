<?php
session_start();

// Cek login dan cart
if (!isset($_SESSION['user']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

include 'config.php';

$cart = $_SESSION['cart']; // Format: [product_name => ['price' => ..., 'quantity' => ...]]

$products = [];
$grandTotal = 0;

foreach ($cart as $name => $item) {
    $price = (float)$item['price'];
    $qty = (int)$item['quantity'];
    $subtotal = $price * $qty;

    $products[] = [
        'name' => $name,
        'price' => $price,
        'qty' => $qty,
        'total' => $subtotal
    ];

    $grandTotal += $subtotal;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Checkout</title>
  <link rel="stylesheet" href="css/product.css" />
  <style>
    body {
      font-family: 'Open Sans', sans-serif;
      margin: 20px;
    }
    .checkout-wrapper {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      justify-content: space-between;
    }
    .checkout-form, .order-summary {
      flex: 1;
      min-width: 300px;
    }
    .order-table {
      width: 100%;
      border-collapse: collapse;
    }
    .order-table th, .order-table td {
      padding: 10px;
      border: 1px solid #ccc;
    }
    .btn-submit {
      padding: 10px 20px;
      background-color: #333;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 15px;
    }
    .btn-submit:hover {
      background-color: #555;
    }
    textarea, select {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    label {
      font-weight: bold;
    }
  </style>
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
