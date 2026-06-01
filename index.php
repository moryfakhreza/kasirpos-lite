<?php
require_once __DIR__ . '/auth.php';
require 'config/db.php';

/* =========================
   DASHBOARD DATA
========================= */
$totalProduct = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalTransaction = $db->query("SELECT COUNT(*) FROM transactions")->fetchColumn();
$totalRevenue = $db->query("SELECT SUM(total) FROM transactions")->fetchColumn();

/* daily revenue */
$daily = $db->query("
    SELECT DATE(created_at) as date, SUM(total) as total
    FROM transactions
    GROUP BY DATE(created_at)
    ORDER BY date DESC
")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h1>Dashboard POS</h1>

<!-- KPI CARDS -->
<div class="dashboard">

    <div class="card">
        <h3>Total Produk</h3>
        <h2><?= $totalProduct ?></h2>
    </div>

    <div class="card">
        <h3>Total Transaksi</h3>
        <h2><?= $totalTransaction ?></h2>
    </div>

    <div class="card">
        <h3>Total Revenue</h3>
        <h2>Rp <?= number_format($totalRevenue ?? 0) ?></h2>
    </div>

</div>

<br>

<!-- DAILY REVENUE -->
<div class="card">
    <h3>Daily Revenue</h3>

    <?php if (count($daily) == 0): ?>
        <p>Belum ada transaksi</p>
    <?php else: ?>

        <table>
            <tr>
                <th>Tanggal</th>
                <th>Total</th>
            </tr>

            <?php foreach ($daily as $d): ?>
            <tr>
                <td><?= $d['date'] ?></td>
                <td>Rp <?= number_format($d['total']) ?></td>
            </tr>
            <?php endforeach; ?>

        </table>

    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>