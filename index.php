<?php
require 'config/db.php';

$totalProduk = $db->query("SELECT COUNT(*) as total FROM products")->fetch()['total'];
$totalStock  = $db->query("SELECT IFNULL(SUM(stock),0) as total FROM products")->fetch()['total'];
$totalValue  = $db->query("SELECT IFNULL(SUM(price * stock),0) as total FROM products")->fetch()['total'];

ob_start();
?>

<h1>Dashboard KasirPOS</h1>

<div class="card">Total Produk: <?= $totalProduk ?></div>
<div class="card">Total Stok: <?= $totalStock ?></div>
<div class="card">Total Nilai: Rp <?= number_format($totalValue) ?></div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>