<?php
require '../config/db.php';

/* =========================
   FILTER DATE
========================= */
$where = "";

if (!empty($_GET['start']) && !empty($_GET['end'])) {
    $start = $_GET['start'];
    $end = $_GET['end'];

    $where = "WHERE created_at BETWEEN '$start 00:00:00' AND '$end 23:59:59'";
}

/* =========================
   GET DATA
========================= */
$transactions = $db->query("
    SELECT * FROM transactions
    $where
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h1>History Transaksi</h1>

<!-- FILTER -->
<div class="card">

    <form method="GET">

        <label>Start Date</label>
        <input type="date" name="start">

        <label>End Date</label>
        <input type="date" name="end">

        <button class="btn-primary" type="submit">
            Filter
        </button>

        <a href="transactions.php" class="btn btn-edit">
            Reset
        </a>

    </form>

</div>

<!-- TABLE -->
<div class="card">

    <table>
        <tr>
            <th>ID</th>
            <th>Total</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>

        <?php if (count($transactions) == 0): ?>
            <tr>
                <td colspan="4">Belum ada transaksi</td>
            </tr>
        <?php endif; ?>

        <?php foreach ($transactions as $t): ?>
        <tr>
            <td>#<?= $t['id'] ?></td>
            <td>Rp <?= number_format($t['total']) ?></td>
            <td><?= $t['created_at'] ?></td>
            <td>
                <a class="btn btn-cart"
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