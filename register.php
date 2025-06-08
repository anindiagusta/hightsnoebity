<?php
session_start();
include 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'] ?? 'customer';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email tidak valid.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } elseif ($role !== 'admin' && $role !== 'customer') {
        $error = "Role tidak valid.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email sudah terdaftar.";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (email, password_hash, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $password_hash, $role);
            if ($stmt->execute()) {
                header("Location: login.php");
            } else {
                $error = "Gagal registrasi, coba lagi.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - HIGHTSNOEBITY</title>
    <link rel="stylesheet" href="css/style-login.css">
</head>
<body>
<div class="container">
    <div class="left-panel">
        <div class="logo">
            <h1>REGISTER HIGHTSNOEBITY</h1>
        </div>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p class="success"><?= $success ?></p>
        <?php else: ?>
            <form method="POST" action="">
                <label for="email">Email</label>
                <input type="email" name="email" placeholder="ex: name@example.com" required>

                <label for="password">Password</label>
                <input type="password" name="password" placeholder="ex: P@SsW0rD" required>

                <input type="hidden" name="role" value="customer">

                <button type="submit">REGISTER</button>
            </form>
        <?php endif; ?>

        <div class="question"><p>Sudah punya akun? <a href="login.php">Login</a></p></div>
    </div>
    <div class="right-panel">
        <img src="images/views/home.png" alt="Together Healthy">
        <div class="overlay-text">
            <h2>New colors</h2>
            <p>now also available in Fashion sizing</p>
        </div>
    </div>
</div>
</body>
</html>
