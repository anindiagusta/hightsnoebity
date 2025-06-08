<?php
session_start();
include 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'] ?? 'customer'; // default customer

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email tidak valid.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } elseif ($role !== 'admin' && $role !== 'customer') {
        $error = "Role tidak valid.";
    } else {
        // Cek apakah email sudah terdaftar
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email sudah terdaftar.";
        } else {
            // Insert user baru
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (email, password_hash, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $password_hash, $role);
            if ($stmt->execute()) {
                $success = "Registrasi berhasil. <a href='login.php'>Login di sini</a>.";
            } else {
                $error = "Gagal registrasi, coba lagi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
<h2>Register User Baru</h2>

<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color:green;"><?= $success ?></p>
<?php else: ?>
<form method="POST" action="">
    Email:<br>
    <input type="email" name="email" required><br><br>

    Password:<br>
    <input type="password" name="password" required><br><br>

    Role:<br>
    <select name="role" required>
        <option value="customer" selected>Customer</option>
        <option value="admin">Admin</option>
    </select><br><br>

    <button type="submit">Register</button>
</form>
<?php endif; ?>

<p><a href="login.php">Sudah punya akun? Login</a></p>

</body>
</html>
