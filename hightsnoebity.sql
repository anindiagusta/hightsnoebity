-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Jun 2025 pada 01.08
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hightsnoebity`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `brands`
--

INSERT INTO `brands` (`id`, `name`, `image_path`) VALUES
(1, 'Adidas', 'images/brands/adidas.png'),
(2, 'Nike', 'images/brands/nike.png'),
(3, 'Zara', 'images/brands/zara.png'),
(4, 'H&M', 'images/brands/hm.png'),
(5, 'Uniqlo', 'images/brands/uniqlo.png'),
(6, 'Levi\'s', 'images/brands/levis.png'),
(7, 'Puma', 'images/brands/puma.png'),
(8, 'Gucci', 'images/brands/gucci.png'),
(9, 'Prada', 'images/brands/prada.png'),
(10, 'Under Armour', 'images/brands/underarmour.png'),
(11, 'New Balance', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(4, 'blouses'),
(1, 'celana'),
(3, 'dress'),
(7, 'jacket'),
(5, 'longpants'),
(2, 'pakaian'),
(6, 'tops');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `status` enum('pending','paid','shipped','completed','canceled') DEFAULT 'pending',
  `total_price` decimal(10,2) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_date`, `status`, `total_price`, `address`, `payment_method`) VALUES
(1, 2, '2025-06-08 11:37:54', 'completed', 262500.00, NULL, NULL),
(2, 1, '2025-06-08 11:45:44', 'completed', 1103500.00, 'Jl. Soekarno Hatta No. 135 Tulungagung, Jawa Timur, Indonesia', 'COD'),
(3, 1, '2025-06-08 11:46:51', 'canceled', 277500.00, 'Jl. Yamin No. 135 Tulungagung, Jawa Timur, Indonesia', 'COD'),
(4, 2, '2025-06-08 11:47:55', 'paid', 1103500.00, 'Jl. Soekarno Hatta No. 135 Tulungagung, Jawa Timur', 'COD'),
(5, 2, '2025-06-08 12:35:36', 'pending', 525000.00, NULL, NULL),
(6, 2, '2025-06-08 12:39:53', 'shipped', 397500.00, 'aa', 'Transfer'),
(7, 2, '2025-06-08 14:05:46', 'pending', 690000.00, 'John Doe Jl. Braga No. 10 Kelurahan Braga Kecamatan Regol Kota Bandung 40111 Indonesia', 'COD');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_name`, `price`, `quantity`) VALUES
(1, 2, 'Casual Dress', 262500.00, 1),
(2, 2, 'Slim Fit Jeans', 100000.00, 1),
(3, 2, 'Winter Jacket', 741000.00, 1),
(4, 3, 'Slim Fit Jeans', 100000.00, 1),
(5, 3, 'Top Tank', 135000.00, 1),
(6, 3, 'Chino Pants', 42500.00, 1),
(7, 4, 'Casual Dress', 262500.00, 1),
(8, 4, 'Slim Fit Jeans', 100000.00, 1),
(9, 4, 'Winter Jacket', 741000.00, 1),
(10, 6, 'Casual Dress', 262500.00, 1),
(11, 6, 'Top Tank', 135000.00, 1),
(12, 7, 'Formal Blouse', 250000.00, 2),
(13, 7, 'Sport T-Shirt', 90000.00, 1),
(14, 7, 'Slim Fit Jeans', 100000.00, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL,
  `gender` enum('MEN','GIRLY') NOT NULL,
  `discount_price` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `image_path` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `harga` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `name`, `brand_id`, `size`, `gender`, `discount_price`, `image_path`, `description`, `stock`, `harga`) VALUES
(1, 'Sport T-Shirt', 1, 'M', 'MEN', 10, 'images/products/sport_tshirt.jpg', 'T-shirt olahraga berbahan dryfit', 50, 100000.00),
(3, 'Casual Dress', 3, 'S', 'GIRLY', 25, 'images/products/casual_dress.jpg', 'Dress santai cocok untuk acara kasual', 20, 350000.00),
(4, 'Formal Blouse', 4, 'M', 'GIRLY', 0, 'images/products/formal_blouse.jpg', 'Blouse formal untuk ke kantor', 25, 250000.00),
(5, 'Slim Fit Jeans', 6, 'L', 'MEN', 0, 'images/products/slim_jeans.jpg', 'Celana jeans slim fit warna biru', 40, 100000.00),
(6, 'Winter Jacket', 5, 'L', 'MEN', 5, 'images/products/winter_jacket.jpg', 'Jaket musim dingin tebal', 15, 780000.00),
(7, 'Top Tank', 4, 'S', 'GIRLY', 10, 'images/products/top_tank.jpg', 'Tank top untuk wanita', 35, 150000.00),
(8, 'Chino Pants', 7, 'M', 'MEN', 50, 'images/products/chino_pants.jpg', 'Celana chino stylish dan nyaman', 18, 85000.00),
(9, 'Mini Skirt', 8, 'S', 'GIRLY', 0, 'images/products/mini_skirt.jpg', 'Rok mini fashionable', 22, 45000.00),
(10, 'Graphic Tee', 10, 'L', 'MEN', 0, 'images/products/graphic_tee.jpg', 'Kaos dengan desain grafis unik', 28, 90000.00),
(11, 'Striped Summer Tee', 1, 'L', 'MEN', 0, 'images/products/DISPLAYB3a.jpg', 'Detail menarik dan potongan pas di badan.', 27, 239487.00),
(12, 'Floral Skater', 1, 'L', 'MEN', 0, 'images/products/DISPLAYB4.jpg', 'Model kekinian dengan sentuhan klasik.', 30, 77097.00),
(13, 'Graphic Print Tee', 1, 'M', 'MEN', 0, 'images/products/DISPLAYB5.jpg', 'Pilihan sempurna untuk tampil menawan setiap saat.', 36, 201456.00),
(14, 'Ruffled Sleeve Blouse', 1, 'M', 'MEN', 25, 'images/products/DISPLAYB6.jpg', 'Tampil stylish dan nyaman dengan bahan berkualitas tinggi.', 16, 237645.00),
(15, 'Pleated Midi Skirt', 1, 'M', 'MEN', 20, 'images/products/DISPLAYB9.jpg', 'Fashionable dan elegan untuk gaya sehari-hari.', 17, 151651.00),
(16, 'Denim Jacket Classic', 1, 'XL', 'MEN', 15, 'images/products/DISPLAYB10.jpg', 'Gaya casual namun tetap terlihat berkelas.', 12, 228787.00),
(17, 'Pleat-Front Skirt', 1, 'S', 'MEN', 0, 'images/products/DISPLAYB10a.jpg', 'Desain modern yang cocok untuk berbagai acara.', 9, 221632.00),
(18, 'Oversized Shirt', 1, 'L', 'MEN', 5, 'images/products/DISPLAYB11.jpg', 'Desain modern yang cocok untuk berbagai acara.', 44, 95959.00),
(19, 'Pastel Wrap Dress', 1, 'XL', 'MEN', 0, 'images/products/DISPLAYB12.jpg', 'Gaya casual namun tetap terlihat berkelas.', 24, 235683.00),
(20, 'Vintage Graphic Tee', 1, 'XL', 'MEN', 0, 'images/products/DISPLAYB13.jpg', 'Gaya casual namun tetap terlihat berkelas.', 46, 124922.00),
(21, 'Lace Trim Top', 1, 'XL', 'GIRLY', 5, 'images/products/DISPLAYB14.jpg', 'Desain modern yang cocok untuk berbagai acara.', 28, 96050.00),
(22, 'Colorblock Polo Shirt', 1, 'L', 'MEN', 15, 'images/products/DISPLAYB15.jpg', 'Fashionable dan elegan untuk gaya sehari-hari.', 37, 135433.00),
(23, 'Breezy Maxi', 1, 'L', 'MEN', 0, 'images/products/DISPLAYB16.jpg', 'Detail menarik dan potongan pas di badan.', 11, 212306.00),
(24, 'Tiered Ruffle Dress', 1, 'S', 'GIRLY', 15, 'images/products/DISPLAYB17.jpg', 'Pilihan sempurna untuk tampil menawan setiap saat.', 37, 131428.00),
(25, 'basic shirts', 1, 'M', 'MEN', 0, 'images/products/DISPLAYB18.jpg', 'Gaya casual namun tetap terlihat berkelas.', 40, 248280.00),
(26, 'Comfy Lounge Tee', 1, 'M', 'MEN', 10, 'images/products/DISPLAYB19.jpg', 'Ideal untuk musim panas maupun musim dingin.', 28, 119353.00),
(27, 'Fit+Flare', 1, 'L', 'MEN', 0, 'images/products/DISPLAYB23.jpg', 'Fashionable dan elegan untuk gaya sehari-hari.', 7, 224488.00),
(28, 'Casual Henley Tee', 1, 'S', 'MEN', 20, 'images/products/DISPLAYB23a.jpg', 'Model kekinian dengan sentuhan klasik.', 41, 161781.00),
(29, 'Tailored Chino Pants', 1, 'XL', 'MEN', 0, 'images/products/DISPLAYB23ab.jpg', 'Cocok dipakai untuk santai maupun acara formal.', 10, 229254.00),
(30, 'Boho Wrap Skirt', 1, 'L', 'MEN', 15, 'images/products/DISPLAYB23abc.jpg', 'Cocok dipakai untuk santai maupun acara formal.', 6, 154283.00),
(31, 'Cropped pants', 1, 'S', 'MEN', 0, 'images/products/DISPLAYB24.jpg', 'Tampil stylish dan nyaman dengan bahan berkualitas tinggi.', 13, 80040.00),
(32, 'Sporty Windbreaker', 1, 'XL', 'GIRLY', 10, 'images/products/DISPLAYB26.jpg', 'Gaya casual namun tetap terlihat berkelas.', 37, 103661.00),
(33, 'Relaxed Fit Tee', 1, 'XL', 'MEN', 0, 'images/products/DISPLAYB27.jpg', 'Gaya casual namun tetap terlihat berkelas.', 31, 237080.00),
(34, 'Pleated Culotte', 1, 'M', 'GIRLY', 15, 'images/products/DISPLAYB28.jpg', 'Model kekinian dengan sentuhan klasik.', 33, 248285.00),
(35, 'Tie-Neck', 1, 'S', 'MEN', 10, 'images/products/DISPLAYB29.jpg', 'Desain modern yang cocok untuk berbagai acara.', 8, 77296.00),
(36, 'High-Waist Shorts', 1, 'XL', 'MEN', 5, 'images/products/DISPLAYB30.jpg', 'Cocok dipakai untuk santai maupun acara formal.', 37, 179836.00),
(37, 'Basic Tee', 1, 'S', 'MEN', 75, 'images/products/DISPLAYB31.jpg', 'Gaya casual namun tetap terlihat berkelas.', 17, 100807.00),
(38, 'Denim Vest', 1, 'M', 'MEN', 0, 'images/products/DISPLAYB31ab.jpg', 'Model kekinian dengan sentuhan klasik.', 21, 133126.00),
(39, 'Pocket Tee', 1, 'S', 'MEN', 0, 'images/products/DISPLAYB33.jpg', 'Pilihan sempurna untuk tampil menawan setiap saat.', 24, 242514.00),
(40, 'Linen Blend Top', 1, 'S', 'GIRLY', 45, 'images/products/DISPLAYB34.jpg', 'Ideal untuk musim panas maupun musim dingin.', 19, 121492.00),
(41, 'Crop Polo', 1, 'M', 'GIRLY', 50, 'images/products/DISPLAYB35.jpg', 'Model kekinian dengan sentuhan klasik.', 41, 79187.00),
(42, 'Utility Shirt', 1, 'M', 'GIRLY', 0, 'images/products/DISPLAYB35a.jpg', 'Desain modern yang cocok untuk berbagai acara.', 27, 211995.00),
(43, 'Casual Chinos', 1, 'XL', 'MEN', 0, 'images/products/DISPLAYB36.jpg', 'Pilihan sempurna untuk tampil menawan setiap saat.', 18, 199143.00),
(44, 'Flowy Maxi Skirt', 1, 'M', 'GIRLY', 15, 'images/products/DISPLAYB38.jpg', 'Pilihan sempurna untuk tampil menawan setiap saat.', 9, 225405.00),
(46, 'Cropped Denim Jacket', 1, 'L', 'MEN', 0, 'images/products/DISPLAYB40.jpg', 'Tampil stylish dan nyaman dengan bahan berkualitas tinggi.', 13, 89633.00),
(47, 'Ruffled Mini Dress', 1, 'S', 'GIRLY', 0, 'images/products/DISPLAYB41.jpg', 'Fashionable dan elegan untuk gaya sehari-hari.', 33, 191546.00),
(48, 'Tie Dye Tee', 1, 'S', 'MEN', 0, 'images/products/DISPLAYB42.jpg', 'Dibuat dari bahan terbaik untuk kenyamanan maksimal.', 33, 127276.00),
(49, 'A-Line Skirt', 1, 'L', 'GIRLY', 20, 'images/products/DISPLAYB43.jpg', 'Detail menarik dan potongan pas di badan.', 26, 187891.00),
(50, 'Pleated Midi Dress', 1, 'M', 'GIRLY', 0, 'images/products/DISPLAYB44.jpg', 'Dibuat dari bahan terbaik untuk kenyamanan maksimal.', 28, 244834.00),
(51, 'Utility Shorts', 1, 'L', 'GIRLY', 35, 'images/products/DISPLAYB45.jpg', 'Ideal untuk musim panas maupun musim dingin.', 8, 204363.00),
(52, 'Relaxed Jacket', 1, 'L', 'GIRLY', 0, 'images/products/DISPLAYB46.jpg', 'Model kekinian dengan sentuhan klasik.', 17, 239754.00),
(53, 'Cropped Hoodie Denim', 1, 'XL', 'GIRLY', 10, 'images/products/DISPLAYB47.jpg', 'Tampil stylish dan nyaman dengan bahan berkualitas tinggi.', 5, 192486.00),
(54, 'Eco Tee', 1, 'S', 'GIRLY', 0, 'images/products/DISPLAYB48.jpg', 'Desain modern yang cocok untuk berbagai acara.', 8, 208520.00),
(56, 'Linen Blend Dress', 1, 'M', 'GIRLY', 5, 'images/products/DISPLAYB50.jpg', 'Dibuat dari bahan terbaik untuk kenyamanan maksimal.', 24, 86472.00),
(58, 'Striped Overshirt', 1, 'XL', 'GIRLY', 60, 'images/products/DISPLAYB52.jpg', 'Tampil stylish dan nyaman dengan bahan berkualitas tinggi.', 15, 196282.00),
(59, 'Pleated Maxi Dress', 1, 'L', 'GIRLY', 5, 'images/products/DISPLAYB53.jpg', 'Fashionable dan elegan untuk gaya sehari-hari.', 9, 110844.00),
(60, 'Outer Comfy', 4, 'M', 'GIRLY', 10, '', 'Cocok untuk kamu yang suka kedinginan', 50, 400000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_categories`
--

CREATE TABLE `product_categories` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `product_categories`
--

INSERT INTO `product_categories` (`product_id`, `category_id`) VALUES
(1, 2),
(3, 3),
(4, 4),
(5, 1),
(6, 2),
(7, 6),
(8, 5),
(9, 3),
(10, 6),
(60, 2),
(60, 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `gender` varchar(10) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `created_at`, `gender`, `phone`, `address`) VALUES
(1, 'ADMIN 1', 'admin@example.com', '$2y$10$2BeZlkdOJMisXQGy1NxfmOXfKTaySdRIzuPgSr4wSp9bAjYUvZmDa', 'admin', '2025-06-08 01:05:59', 'Female', '08112234455', '13th Street. 47 W 13th St, New York, NY 10011, USA. 20 Cooper Square. 20 Cooper Square, New York, NY 10003, USA'),
(2, 'Fiesta Viennes Tiffin', 'customer@example.com', '$2y$10$UwMvCdTgC3psD31PjVk7D.rP2bd6G7kDV35IksFAV08QDX4XBL4.m', 'customer', '2025-06-08 01:22:04', 'Female', '081567234891', 'John Doe Jl. Braga No. 10 Kelurahan Braga Kecamatan Regol Kota Bandung 40111 Indonesia'),
(3, 'Kylian Mbappe', 'customer2@example.com', '$2y$10$R5X.KssTz/vX7SLavn9hSejWkiR4k0ozub3cj5vIhEcUrn6JyVYbO', 'customer', '2025-06-08 07:57:51', 'Male', '087654321345', 'Avenida de Concha Espina, nº 1, Madrid, 28036, Spain'),
(4, 'Jack Sparrow', 'customer3@example.com', '$2y$10$ZdPC1K5AJyvyzDuAclpNfu43/lnT9ON69rXZxOydPOD0IAJ264lTG', 'customer', '2025-06-08 08:00:03', 'Male', '08978686544', 'Wallilabou Bay, located on St. Vincent and the Grenadines'),
(5, 'ADMIN 2', 'admin2@example.com', '$2y$10$CzwQH8lXz4fNbZ6BvLA59upt/Wow7LY5yBxEZWLBP4KapXg1SY.K2', 'admin', '2025-06-11 09:46:47', 'Male', '087654321234', 'Avenida de Concha Espina, nº 1, Madrid, 28036, Spain');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indeks untuk tabel `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`);

--
-- Ketidakleluasaan untuk tabel `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
