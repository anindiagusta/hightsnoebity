<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$userId = $_SESSION['user']['id'];
$grandTotal = $_POST['grand_total'] ?? 0;
$orderDate = date("Y-m-d H:i:s");
$status = 'pending';

// Simpan order ke database
$stmt = $conn->prepare("INSERT INTO orders (user_id, order_date, status, total_price) VALUES (?, ?, ?, ?)");
$stmt->bind_param("issd", $userId, $orderDate, $status, $grandTotal);
$stmt->execute();

// Kosongkan keranjang
unset($_SESSION['cart']);

// Redirect ke riwayat pesanan
header("Location: orders.php");
exit;
