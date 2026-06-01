<?php
session_start();
require '../config/db.php';

/* =========================
   INIT CART
========================= */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* =========================
   ADD TO CART
========================= */
if (isset($_GET['add'])) {
    $id = $_GET['add'];

    $stmt = $db->prepare("SELECT * FROM products WHERE id=?");
    $stmt->execute([$id]);
    $p = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$p) exit;

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty'] += 1;
    } else {
        $_SESSION['cart'][$id] = $p;
        $_SESSION['cart'][$id]['qty'] = 1;
    }

    echo json_encode($_SESSION['cart']);
    exit;
}

/* =========================
   UPDATE QTY
========================= */
if (isset($_GET['update'])) {
    $id = $_GET['id'];
    $type = $_GET['type'];

    if (isset($_SESSION['cart'][$id])) {

        if ($type == "plus") {
            $_SESSION['cart'][$id]['qty']++;
        } else {
            $_SESSION['cart'][$id]['qty']--;
        }

        if ($_SESSION['cart'][$id]['qty'] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }

    echo json_encode($_SESSION['cart']);
    exit;
}

/* =========================
   REMOVE ITEM
========================= */
if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    echo json_encode($_SESSION['cart']);
    exit;
}

/* =========================
   CHECKOUT TRANSACTION
========================= */
if (isset($_POST['checkout'])) {

    $total = 0;

    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['qty'];
    }

    // insert transaction
    $db->prepare("INSERT INTO transactions (total) VALUES (?)")
       ->execute([$total]);

    $transaction_id = $db->lastInsertId();

    // insert items + reduce stock
    foreach ($_SESSION['cart'] as $id => $item) {

        $db->prepare("INSERT INTO transaction_items 
            (transaction_id, product_id, qty, price)
            VALUES (?, ?, ?, ?)")
        ->execute([
            $transaction_id,
            $id,
            $item['qty'],
            $item['price']
        ]);

        $db->prepare("UPDATE products SET stock = stock - ? WHERE id=?")
        ->execute([$item['qty'], $id]);
    }

    $_SESSION['cart'] = [];

    header("Location: products.php");
    exit;
}

/* =========================
   GET PRODUCTS
========================= */
$products = $db->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<div class="pos-layout">

    <!-- PRODUCT AREA -->
    <div class="product-area">

        <h1>POS Kasir</h1>

        <div class="product-grid">

            <?php foreach ($products as $p): ?>

            <div class="product-card">
                <h3><?= htmlspecialchars($p['name']) ?></h3>
                <p>Rp <?= number_format($p['price']) ?></p>
                <p>Stok: <?= $p['stock'] ?></p>

                <button class="btn btn-cart"
                        onclick="addToCart(<?= $p['id'] ?>)">
                    + Add to Cart
                </button>
            </div>

            <?php endforeach; ?>

        </div>
    </div>

    <!-- CART SIDEBAR -->
    <div class="cart-sidebar">
        <h2>Cart</h2>

        <div id="cartBox"></div>

        <h3 id="total">Total: Rp 0</h3>

        <form method="POST">
            <button class="btn-primary" name="checkout">
                Checkout
            </button>
        </form>
    </div>

</div>

<script>
let cart = <?php echo json_encode($_SESSION['cart']); ?>;

function renderCart() {
    let html = "";
    let total = 0;

    for (let id in cart) {
        let item = cart[id];
        let subtotal = item.price * item.qty;
        total += subtotal;

        html += `
        <div class="cart-item">
            <div>
                <b>${item.name}</b><br>
                Rp ${item.price}
            </div>

            <div>
                <button onclick="updateQty(${id},'minus')">-</button>
                ${item.qty}
                <button onclick="updateQty(${id},'plus')">+</button>
            </div>

            <button onclick="removeItem(${id})">x</button>
        </div>
        `;
    }

    document.getElementById("cartBox").innerHTML = html;
    document.getElementById("total").innerText = "Total: Rp " + total;
}

function addToCart(id) {
    fetch("?add=" + id)
    .then(res => res.json())
    .then(data => {
        cart = data;
        renderCart();
    });
}

function updateQty(id, type) {
    fetch("?update=1&id=" + id + "&type=" + type)
    .then(res => res.json())
    .then(data => {
        cart = data;
        renderCart();
    });
}

function removeItem(id) {
    fetch("?remove=" + id)
    .then(res => res.json())
    .then(data => {
        cart = data;
        renderCart();
    });
}

renderCart();
</script>

<?php
$content = ob_get_clean();
include '../layout.php';
?>