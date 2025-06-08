<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 Not Found</h1>";
    exit;
}

// Pagination setup
$limit = 20;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// --- HANDLE CREATE / UPDATE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'];
    $brand_id = $_POST['brand_id'];
    $size = $_POST['size'];
    $gender = $_POST['gender'];
    $discount_price = $_POST['discount_price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];
    $harga = $_POST['harga'];
    $category_ids = $_POST['category_ids'] ?? [];

    // Ambil path gambar lama (kalau edit)
    $oldImagePath = $_POST['old_image_path'] ?? '';

    // Upload gambar jika ada
    $image_path = $oldImagePath;
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'images/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $tmpName = $_FILES['image_file']['tmp_name'];
        $fileName = basename($_FILES['image_file']['name']);
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($ext, $allowedExt)) {
            $newFileName = uniqid('prod_') . '.' . $ext;
            $targetPath = $uploadDir . $newFileName;

            if (move_uploaded_file($tmpName, $targetPath)) {
                $image_path = $targetPath;

                // Hapus file lama jika ada dan beda
                if ($oldImagePath && file_exists($oldImagePath) && $oldImagePath !== $image_path) {
                    unlink($oldImagePath);
                }
            } else {
                $_SESSION['error'] = "Gagal meng-upload file gambar.";
            }
        } else {
            $_SESSION['error'] = "Format file gambar tidak didukung. Gunakan jpg, jpeg, png, gif.";
        }
    }

    if ($id) {
        // UPDATE
        $sql = "UPDATE products SET name=?, brand_id=?, size=?, gender=?, discount_price=?, image_path=?, description=?, stock=?, harga=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisssssidi", $name, $brand_id, $size, $gender, $discount_price, $image_path, $description, $stock, $harga, $id);
        $stmt->execute();

        // Sync categories
        $conn->query("DELETE FROM product_categories WHERE product_id=$id");
        foreach ($category_ids as $cat_id) {
            $cat_id = (int)$cat_id;
            $conn->query("INSERT INTO product_categories (product_id, category_id) VALUES ($id, $cat_id)");
        }
    } else {
        // INSERT
        $sql = "INSERT INTO products (name, brand_id, size, gender, discount_price, image_path, description, stock, harga) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisssssii", $name, $brand_id, $size, $gender, $discount_price, $image_path, $description, $stock, $harga);
        $stmt->execute();
        $new_id = $conn->insert_id;

        foreach ($category_ids as $cat_id) {
            $cat_id = (int)$cat_id;
            $conn->query("INSERT INTO product_categories (product_id, category_id) VALUES ($new_id, $cat_id)");
        }
    }

    header("Location: products-crud.php");
    exit;
}

// --- HANDLE DELETE ---
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    // Hapus gambar dari storage sebelum delete
    $res = $conn->query("SELECT image_path FROM products WHERE id=$id");
    if ($res && $row = $res->fetch_assoc()) {
        if ($row['image_path'] && file_exists($row['image_path'])) {
            unlink($row['image_path']);
        }
    }

    $conn->query("DELETE FROM product_categories WHERE product_id = $id");
    $conn->query("DELETE FROM products WHERE id = $id");
    header("Location: products-crud.php");
    exit;
}

// --- FETCH BRANDS & CATEGORIES ---
$brands = $conn->query("SELECT * FROM brands");
$categories = $conn->query("SELECT * FROM categories");

// --- EDIT MODE ---
$editData = null;
$selected_categories = [];

if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $result = $conn->query("SELECT * FROM products WHERE id = $edit_id");
    $editData = $result->fetch_assoc();

    $catResult = $conn->query("SELECT category_id FROM product_categories WHERE product_id = $edit_id");
    while ($row = $catResult->fetch_assoc()) {
        $selected_categories[] = $row['category_id'];
    }
}

// --- FETCH PRODUCTS LIST WITH PAGINATION ---
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM products");
$totalCount = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalCount / $limit);

$productList = $conn->query("
    SELECT p.*, b.name AS brand_name 
    FROM products p 
    JOIN brands b ON p.brand_id = b.id 
    ORDER BY p.id DESC
    LIMIT $limit OFFSET $offset
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>CRUD Produk</title>
    <link rel="stylesheet" href="css/CRUD.css" />
</head>
<body>

<?php include 'header-admin.php'; ?>

<section class="crud-section">
<div class="container">

    <div class="form-container">
        <h2><?= $editData ? 'Edit Product' : 'Add New Product' ?></h2>

        <form method="POST">
            <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">

            <div>
                <label>Product Name</label>
                <input type="text" name="name" required value="<?= htmlspecialchars($editData['name'] ?? '') ?>">
            </div>

            <div>
                <label>Brand</label>
                <select name="brand_id" required>
                    <option value="">-- Select Brand --</option>
                    <?php
                    $brands->data_seek(0);
                    while ($b = $brands->fetch_assoc()): ?>
                        <option value="<?= $b['id'] ?>" <?= isset($editData['brand_id']) && $editData['brand_id'] == $b['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($b['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label>Size</label>
                <input type="text" name="size" value="<?= htmlspecialchars($editData['size'] ?? '') ?>">
            </div>

            <div>
                <label>Gender</label>
                <select name="gender">
                    <option value="MEN" <?= ($editData['gender'] ?? '') == 'MEN' ? 'selected' : '' ?>>MEN</option>
                    <option value="GIRLY" <?= ($editData['gender'] ?? '') == 'GIRLY' ? 'selected' : '' ?>>GIRLY</option>
                </select>
            </div>

            <div>
                <label>Discount (%)</label>
                <input type="number" name="discount_price" value="<?= htmlspecialchars($editData['discount_price'] ?? 0) ?>" min="0" max="100">
            </div>

            <div>
                <label>Upload Image</label>
                <input type="file" name="image_file" accept="image/*" <?= $editData ? '' : 'required' ?>>
                <?php if (!empty($editData['image_path'])): ?>
                    <p>Current image:</p>
                    <img src="<?= htmlspecialchars($editData['image_path']) ?>" alt="Current Image"/>
                <?php endif; ?>
            </div>

            <div>
                <label>Description</label>
                <textarea name="description"><?= htmlspecialchars($editData['description'] ?? '') ?></textarea>
            </div>

            <div>
                <label>Stock</label>
                <input type="number" name="stock" value="<?= htmlspecialchars($editData['stock'] ?? 0) ?>" min="0">
            </div>

            <div>
                <label>Harga</label>
                <input type="number" name="harga" value="<?= htmlspecialchars($editData['harga'] ?? 0) ?>" min="0">
            </div>

            <div class="categories">
                <label>Categories</label>
                <?php
                $categories->data_seek(0);
                foreach ($categories as $cat): ?>
                    <label>
                        <input type="checkbox" name="category_ids[]" value="<?= $cat['id'] ?>"
                            <?= in_array($cat['id'], $selected_categories) ? 'checked' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <button type="submit"><?= $editData ? 'Update' : 'Add' ?> Product</button>
        </form>
    </div>

    <div class="list-container">
        <h2>Product List</h2>
        <table>
            <thead>
            <tr>
                <th>Name</th>
                <th>Brand</th>
                <th>Size</th>
                <th>Gender</th>
                <th>Harga</th>
                <th>Diskon</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $productList->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['brand_name']) ?></td>
                    <td><?= htmlspecialchars($row['size']) ?></td>
                    <td><?= htmlspecialchars($row['gender']) ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td><?= $row['discount_price'] ?>%</td>
                    <td><?= $row['stock'] ?></td>
                    <td>
                        <a href="?edit=<?= $row['id'] ?>&page=<?= $page ?>">Edit</a> |
                        <a href="?delete=<?= $row['id'] ?>&page=<?= $page ?>" onclick="return confirm('Hapus produk ini?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>">&laquo; Prev</a>
            <?php else: ?>
                <span>&laquo; Prev</span>
            <?php endif; ?>

            <?php
            $start = max(1, $page - 2);
            $end = min($totalPages, $page + 2);
            for ($i = $start; $i <= $end; $i++):
                if ($i == $page):
                    echo "<span class='current'>$i</span>";
                else:
                    echo "<a href='?page=$i'>$i</a>";
                endif;
            endfor;
            ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>">Next &raquo;</a>
            <?php else: ?>
                <span>Next &raquo;</span>
            <?php endif; ?>
        </div>
    </div>
</div>
</section>

</body>
</html>
