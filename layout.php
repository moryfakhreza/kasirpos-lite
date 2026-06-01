<!DOCTYPE html>
<html>
<head>
    <title>KasirPOS Lite</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>

<div class="app">

    <!-- LEFT SIDEBAR -->
    <div class="sidebar">
        <h2>KasirPOS</h2>

        <a href="/index.php">Dashboard</a>
        <a href="/pages/products.php">Produk</a>
        <a href="/pages/add.php">Tambah</a>
        <a href="/pages/cart.php">Cart</a>
        <a href="/pages/transactions.php">Transaksi</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main">
        <?= $content ?>
    </div>

</div>

</body>
</html>