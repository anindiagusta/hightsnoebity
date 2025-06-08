<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$userId = $_SESSION['user']['id'];
$grandTotal = $_POST['grand_total'] ?? 0;
$address = $_POST['address'] ?? '';
$paymentMethod = $_POST['payment_method'] ?? '';
$orderDate = date("Y-m-d H:i:s");
$status = 'pending';

// Simpan ke tabel orders (tambahkan kolom address dan payment_method di DB jika belum ada)
$stmt = $conn->prepare("INSERT INTO orders (user_id, order_date, status, total_price, address, payment_method) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issdss", $userId, $orderDate, $status, $grandTotal, $address, $paymentMethod);
$stmt->execute();

$orderId = $stmt->insert_id;

// Simpan detail produk ke order_items (jika tabel tersedia)
if (isset($_SESSION['cart'])) {
    $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_name, price, quantity) VALUES (?, ?, ?, ?)");
    foreach ($_SESSION['cart'] as $name => $item) {
        $stmtItem->bind_param("isdi", $orderId, $name, $item['price'], $item['quantity']);
        $stmtItem->execute();
    }
    $stmtItem->close();
}

// Kosongkan cart
unset($_SESSION['cart']);

header("Location: orders.php");
exit;
