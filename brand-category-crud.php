<?php
session_start();

// include database configuration
include 'config.php';

// hak akses hanya untuk admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 Not Found</h1>";
    exit;
}

// Action untuk menambahkan atau mengupdate brand atau kategori
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = $_POST['table'];
    $id = $_POST['id'] ?? '';
    $name = trim($_POST['name']);

    if ($id) { // Jika ID ada, berarti update
        $stmt = $conn->prepare("UPDATE $table SET name=? WHERE id=?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
    } else { // Jika ID tidak ada, berarti insert
        $stmt = $conn->prepare("INSERT INTO $table (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
    }

    // Redirect setelah operasi
    header("Location: brand-category-crud.php");
    exit;
}

// Action untuk menghapus brand atau kategori
if (isset($_GET['delete']) && isset($_GET['table'])) { // Cek apakah ada parameter delete dan table
    $id = (int)$_GET['delete'];
    $table = $_GET['table']; 
    $conn->query("DELETE FROM $table WHERE id = $id"); // Hapus data berdasarkan ID dan table
    // Redirect setelah delete
    header("Location: brand-category-crud.php");
    exit;
}

// Action untuk mengedit brand atau kategori
$editBrand = null; // Inisialisasi variabel untuk menyimpan data yang akan diedit
$editCategory = null; // Inisialisasi variabel untuk menyimpan data yang akan diedit

if (isset($_GET['edit']) && isset($_GET['table'])) { // Cek apakah ada parameter edit dan table
    $id = (int)$_GET['edit'];
    $table = $_GET['table'];
    $result = $conn->query("SELECT * FROM $table WHERE id = $id"); // Ambil data berdasarkan ID dan table
    if ($row = $result->fetch_assoc()) { // Jika ada data yang ditemukan
        if ($table === 'brands') $editBrand = $row; // Simpan data brand yang akan diedit
        if ($table === 'categories') $editCategory = $row; // Simpan data kategori yang akan diedit
    }
}

// Actions untuk mengambil data brands dan categories
$brands = $conn->query("SELECT * FROM brands ORDER BY id DESC"); // Ambil semua data brands
$categories = $conn->query("SELECT * FROM categories ORDER BY id DESC"); // Ambil semua data categories
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brand & Category Management</title>
    <link rel="stylesheet" href="css/CRUD.css">
</head>
<body>

<?php include 'header-admin.php'; ?>

<section class="crud-section">
<div class="container">
    <div class="crud-group">
    <h2>Manage Brands</h2>
    <div class="form-container">
        <form method="POST">
            <input type="hidden" name="table" value="brands">
            <input type="hidden" name="id" value="<?= $editBrand['id'] ?? '' ?>">
            <input type="text" name="name" placeholder="Brand Name" required value="<?= htmlspecialchars($editBrand['name'] ?? '') ?>">
            <button type="submit"><?= $editBrand ? 'Update' : 'Add' ?> Brand</button>
        </form>
    </div>
    <table>
        <thead><tr><th>ID</th><th>Name</th><th>Actions</th></tr></thead>
        <tbody>
        <?php while ($row = $brands->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td class="actions">
                    <a href="?edit=<?= $row['id'] ?>&table=brands">Edit</a>
                    <a href="?delete=<?= $row['id'] ?>&table=brands" onclick="return confirm('Hapus brand ini?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </div>

    <div class="crud-group">
    <h2>Manage Categories</h2>
    <div class="form-container">
        <form method="POST">
            <input type="hidden" name="table" value="categories">
            <input type="hidden" name="id" value="<?= $editCategory['id'] ?? '' ?>">
            <input type="text" name="name" placeholder="Category Name" required value="<?= htmlspecialchars($editCategory['name'] ?? '') ?>">
            <button type="submit"><?= $editCategory ? 'Update' : 'Add' ?> Category</button>
        </form>
    </div>
    <table>
        <thead><tr><th>ID</th><th>Name</th><th>Actions</th></tr></thead>
        <tbody>
        <?php while ($row = $categories->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td class="actions">
                    <a href="?edit=<?= $row['id'] ?>&table=categories">Edit</a>
                    <a href="?delete=<?= $row['id'] ?>&table=categories" onclick="return confirm('Hapus kategori ini?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </div>
</div>
</section>

</body>
</html>
