<?php
session_start();
include 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, email, password_hash, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $email_db, $password_hash, $role);
        $stmt->fetch();

        if (password_verify($password, $password_hash)) {
            $_SESSION['user'] = [
                'id' => $id,
                'email' => $email_db,
                'role' => $role
            ];

            // Arahkan berdasarkan role
            if ($role === 'admin') {
                header("Location: home-admin.php");
            } elseif ($role === 'customer') {
                header("Location: home.php");
            } else {
                $error = "Peran tidak dikenali.";
            }
            exit;
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "User tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login HIGHTSNOEBITY</title>
    <link rel="stylesheet" href="css/style-login.css">
</head>
<body>
<div class="container">
    <div class="left-panel">
        <div class="logo">
            <h1>LOGIN HIGHTSNOEBITY</h1>
        </div>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" action="">
            <label for="email">Email</label>
            <input type="email" name="email" placeholder="ex: name@example.com" required>

            <label for="password">Password</label>
            <input type="password" name="password" placeholder="ex: P@SsW0rD" required>

            <button type="submit">LOGIN</button>
        </form>

        <div class="question"><p>Belum punya akun? <a href="Register.php">Register</a></p></div>
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
