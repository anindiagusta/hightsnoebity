<?php
session_start();
include 'config.php';

// Hanya izinkan jika sudah login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$isAdmin = $user['role'] === 'admin';

// Admin bisa ubah status order
if ($isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['status'];
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $orderId);
    $stmt->execute();
    header("Location: orders.php");
    exit;
}

// Ambil data pesanan
if ($isAdmin) {
    $query = "SELECT o.*, u.email FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.order_date DESC";
    $result = $conn->query($query);
} else {
    $userId = $user['id'];
    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History</title>
    <link rel="stylesheet" href="css/orders.css">
</head>
<body>


<?php
if ($isAdmin) {
    include 'header-admin.php';
} else {
    include 'header.php';
}
?>

<section>
<div class="container">
    <h2><?= $isAdmin ? 'All Orders (Admin)' : 'My Order History' ?></h2>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <?php if ($isAdmin): ?><th>User</th><?php endif; ?>
                <th>Order Date</th>
                <th>Total Price</th>
                <th>Status</th>
                <?php if ($isAdmin): ?><th>Action</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <?php if ($isAdmin): ?><td><?= htmlspecialchars($order['email']) ?></td><?php endif; ?>
                    <td><?= $order['order_date'] ?></td>
                    <td>Rp <?= number_format($order['total_price'], 0, ',', '.') ?></td>
                    <td><?= $order['status'] ?></td>
                    <?php if ($isAdmin): ?>
                        <td>
                            <form method="POST" class="inline-form">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <select name="status">
                                    <?php
                                    $statuses = ['pending', 'paid', 'shipped', 'completed', 'canceled'];
                                    foreach ($statuses as $status) {
                                        $selected = $order['status'] === $status ? 'selected' : '';
                                        echo "<option value=\"$status\" $selected>$status</option>";
                                    }
                                    ?>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</section>

</body>
</html>
