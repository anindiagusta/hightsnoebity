<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user']['id'];
$isAdmin = isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';

// Ambil data user dari database
$stmt = $conn->prepare("SELECT name, email, gender, phone, address FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Profile</title>
  <link rel="stylesheet" href="css/form.css">
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
        <div class="profile-container">
            <h2>My Profile</h2>
            <div class="profile-info">
            <label>Name:</label>
            <p><?= htmlspecialchars($userData['name']) ?></p>

            <label>Email:</label>
            <p><?= htmlspecialchars($userData['email']) ?></p>

            <label>Gender:</label>
            <p><?= htmlspecialchars($userData['gender']) ?: '-' ?></p>

            <label>Phone:</label>
            <p><?= htmlspecialchars($userData['phone']) ?: '-' ?></p>

            <label>Address:</label>
            <p><?= nl2br(htmlspecialchars($userData['address'])) ?: '-' ?></p>
            </div>

            <?php
            if ($isAdmin) { ?>
                <div class="actions">
                <a style="padding: 12px 12px;"; href="edit_profile.php">Edit Profile</a>
                <a style="padding: 12px 12px;"; href="register-admin.php">Add Admin</a>
                <a style="padding: 12px 12px;"; href="manage_users.php">Manage Users</a>
                <a style="padding: 12px 12px;"; href="logout.php">Logout</a>
                </div> 
            <?php } else { ?>
                <div class="actions">
                <a href="edit_profile.php">Edit Profile</a>
                <a href="orders.php"> My Orders</a>
                <a href="logout.php">Logout</a>
                </div>
            <?php } ?>
            
        </div>
    </section>
</body>
</html>
