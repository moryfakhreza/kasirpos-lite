<?php
require '../config/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = trim($_POST['name']);
    $price = trim($_POST['price']);
    $stock = trim($_POST['stock']);

    // VALIDASI
    if ($name == "" || $price == "" || $stock == "") {
        $error = "Semua field wajib diisi!";
    } else {
        $stmt = $db->prepare("INSERT INTO products (name, price, stock) VALUES (?, ?, ?)");
        $stmt->execute([$name, $price, $stock]);

        header("Location: products.php");
        exit;
    }
}

ob_start();
?>

<h1>Tambah Produk</h1>

<div class="card">

<?php if ($error): ?>
    <p style="color:red;"><?= $error ?></p>
<?php endif; ?>

<form method="POST">
    Nama:<br>
    <input type="text" name="name"><br><br>

    Harga:<br>
    <input type="number" name="price"><br><br>

    Stok:<br>
    <input type="number" name="stock"><br><br>

    <button type="submit" class="btn-primary">Simpan</button>
</form>

</div>

<?php
$content = ob_get_clean();
include '../layout.php';
?>