<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// add
if (isset($_GET['add'])) {
    $id = $_GET['add'];

    $stmt = $db->prepare("SELECT * FROM products WHERE id=?");
    $stmt->execute([$id]);
    $p = $stmt->fetch();

    $_SESSION['cart'][$id] = $p;
    header("Location: cart.php");
    exit;
}

// remove
if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    header("Location: cart.php");
    exit;
}

// checkout
if (isset($_POST['checkout'])) {
    $total = 0;

    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['stock'];
    }

    $db->prepare("INSERT INTO transactions (total) VALUES (?)")
       ->execute([$total]);

    $_SESSION['cart'] = [];

    header("Location: cart.php");
    exit;
}
?>

<?php ob_start(); ?>

<h1>Checkout Kasir</h1>

<div class="cart-wrapper">

    <!-- CART TABLE -->
    <div class="cart-box">

        <h3>Keranjang</h3>

        <table>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Total</th>
                <th></th>
            </tr>

            <?php $grand = 0; ?>

            <?php foreach ($_SESSION['cart'] as $id => $item): ?>
            <?php $total = $item['price'] * $item['stock']; ?>
            <?php $grand += $total; ?>

            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= $item['price'] ?></td>
                <td><?= $item['stock'] ?></td>
                <td><?= $total ?></td>
                <td>
                    <a class="btn-remove" href="?remove=<?= $id ?>">X</a>
                </td>
            </tr>
            <?php endforeach; ?>

        </table>

    </div>

    <!-- SUMMARY -->
    <div class="summary-box">

        <h3>Ringkasan</h3>

        <p>Total Pembelian</p>
        <div class="total">
            Rp <?= number_format($grand) ?>
        </div>

        <form method="POST">
            <button class="btn-checkout" name="checkout">
                Checkout
            </button>
        </form>

        <br>
        <a href="products.php">← Kembali belanja</a>

    </div>

</div>

<?php
$content = ob_get_clean();
include '../layout.php';
?>