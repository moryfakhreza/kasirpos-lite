<?php
require '../config/db.php';

$transactions = $db->query("
    SELECT * FROM transactions
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h1>History Transaksi</h1>

<div class="card">

<table>
    <tr>
        <th>ID</th>
        <th>Total</th>
        <th>Tanggal</th>
        <th>Aksi</th>
    </tr>

    <?php foreach ($transactions as $t): ?>
    <tr>
        <td>#<?= $t['id'] ?></td>
        <td>Rp <?= number_format($t['total']) ?></td>
        <td><?= $t['created_at'] ?></td>
        <td>
            <a class="btn btn-edit"
               href="invoice.php?id=<?= $t['id'] ?>">
               Detail
            </a>
        </td>
    </tr>
    <?php endforeach; ?>

</table>

</div>

<?php
$content = ob_get_clean();
include '../layout.php';
?>