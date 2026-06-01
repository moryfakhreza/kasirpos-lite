<?php
require_once '../auth.php';
require '../config/db.php';

$id = $_GET['id'];

// ambil transaksi
$trx = $db->prepare("SELECT * FROM transactions WHERE id=?");
$trx->execute([$id]);
$trx = $trx->fetch();

// ambil item
$stmt = $db->prepare("
    SELECT ti.*, p.name
    FROM transaction_items ti
    JOIN products p ON p.id = ti.product_id
    WHERE ti.transaction_id = ?
");
$stmt->execute([$id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h1>Invoice #<?= $trx['id'] ?></h1>

<div class="card">

<p><b>Tanggal:</b> <?= $trx['created_at'] ?></p>

<table>
    <tr>
        <th>Produk</th>
        <th>Qty</th>
        <th>Harga</th>
        <th>Total</th>
    </tr>

    <?php foreach ($items as $i): ?>
    <tr>
        <td><?= $i['name'] ?></td>
        <td><?= $i['qty'] ?></td>
        <td>Rp <?= number_format($i['price']) ?></td>
        <td>Rp <?= number_format($i['qty'] * $i['price']) ?></td>
    </tr>
    <?php endforeach; ?>

</table>

<h2>Total: Rp <?= number_format($trx['total']) ?></h2>

</div>

<?php
$content = ob_get_clean();
include '../layout.php';
?>