<?php
session_start();

// Inisialisasi cart session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Tangkap data produk dari POST
$productName = $_POST['product_name'] ?? '';
$productPrice = $_POST['product_price'] ?? 0;

if ($productName) {
    // Jika produk sudah ada di cart, tambah jumlahnya
    if (isset($_SESSION['cart'][$productName])) {
        $_SESSION['cart'][$productName]['quantity'] += 1;
    } else { // Jika produk belum ada, tambahkan ke cart
        $_SESSION['cart'][$productName] = [
            'price' => (float)$productPrice,
            'quantity' => 1,
        ];
    }
}

// Setelah tambah ke cart, redirect ke halaman cart atau produk
header('Location: cart.php');

exit;
