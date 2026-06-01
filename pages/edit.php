<?php
require '../config/db.php';

$id = $_GET['id'];

// ambil data lama
$stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Produk tidak ditemukan");
}

// proses update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $update = $db->prepare("UPDATE products SET name=?, price=?, stock=? WHERE id=?");
    $update->execute([$name, $price, $stock, $id]);

    header("Location: products.php");
    exit;
}

ob_start();
?>

<h1>Edit Produk</h1>

<div class="card">
<form method="POST">
    Nama:<br>
    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
    <br><br>

    Harga:<br>
    <input type="number" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>
    <br><br>

    Stok:<br>
    <input type="number" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required>
    <br><br>

<button type="submit" class="btn-primary">Simpan Perubahan</button>
</form>
</div>

<br>
<a href="products.php">← Kembali</a>

<?php
$content = ob_get_clean();
include '../layout.php';
?>