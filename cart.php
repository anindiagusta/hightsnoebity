<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Shopping Cart</title>
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
      background-color: #c8815f;
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
    }
    a.button {
      background-color: #c8815f;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 8px;
    }
    a.button:hover {
      background-color: #555;
    }
  </style>
</head>
<body>

<h2>Your Shopping Cart</h2>

<?php if (!empty($_SESSION['cart'])): ?>

<table>
  <thead>
    <tr>
      <th>Product Name</th>
      <th>Price (Rp)</th>
      <th>Quantity</th>
      <th>Subtotal (Rp)</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $grandTotal = 0;
    foreach ($_SESSION['cart'] as $name => $item):
        $subtotal = $item['price'] * $item['quantity'];
        $grandTotal += $subtotal;
    ?>
    <tr>
      <td><?= htmlspecialchars($name) ?></td>
      <td><?= number_format($item['price'], 0, ',', '.') ?></td>
      <td><?= $item['quantity'] ?></td>
      <td><?= number_format($subtotal, 0, ',', '.') ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="total">Grand Total: Rp <?= number_format($grandTotal, 0, ',', '.') ?></div>

<a href="products.php" class="button">Continue Shopping</a>

<?php else: ?>
  <p class="empty-cart">Your cart is empty.</p>
  <a href="products.php" class="button">Shop Now</a>
<?php endif; ?>

</body>
</html>
