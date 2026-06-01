<?php
require_once __DIR__ . '/auth.php';
?>
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
        <p style="color:white;">
    👤 <?= $_SESSION['user']['username'] ?>
</p>

<?php if (isset($_SESSION['user'])): ?>

    <?php if ($_SESSION['user']['role'] == 'admin'): ?>
        <a href="/index.php">Dashboard</a>
        <a href="/pages/products.php">Produk</a>
        <a href="/pages/transactions.php">Transaksi</a>
    <?php else: ?>
        <a href="/pages/products.php">POS</a>
    <?php endif; ?>

    <p><?= $_SESSION['user']['username'] ?></p>
    <a href="/logout.php">Logout</a>

<?php else: ?>

    <a href="/login.php">Login</a>

<?php endif; ?>
        
    </div>

    <!-- MAIN CONTENT -->
    <div class="main">
        <?= $content ?>
    </div>

</div>

</body>
</html>