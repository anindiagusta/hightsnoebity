<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user']['id'];
$success = $error = "";

// Ambil data user
$stmt = $conn->prepare("SELECT name, email, gender, phone, address FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Proses update jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $gender = $_POST['gender'];
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if ($name === "") {
        $error = "Name cannot be empty.";
    } else {
        $update = $conn->prepare("UPDATE users SET name = ?, gender = ?, phone = ?, address = ? WHERE id = ?");
        $update->bind_param("ssssi", $name, $gender, $phone, $address, $userId);
        if ($update->execute()) {
            $_SESSION['user']['name'] = $name;
            header("Location: profile.php");
            exit;
        } else {
            $error = "Failed to update profile.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="css/form.css">
</head>
<body>

<?php include 'header.php'; ?>

<section>
    <div class="profile-container">
        <h2>Edit Profile</h2>

        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($success): ?>
            <div class="message success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" class="profile-info">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required value="<?= htmlspecialchars($user['name']) ?>">

            <label for="gender">Gender:</label>
            <select name="gender" id="gender">
                <option value="">-- Select --</option>
                <option value="Male" <?= $user['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $user['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
            </select>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($user['phone']) ?>">

            <label for="address">Address:</label>
            <textarea name="address" id="address" rows="4"><?= htmlspecialchars($user['address']) ?></textarea>

            <div class="actions">
                <button type="submit">Update</button>
                <a href="profile.php">Cancel</a>
            </div>
        </form>
    </div>
</section>

</body>
</html>
