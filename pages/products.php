<?php
require '../config/db.php';
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ADD TO CART (AJAX SIMPLE)
if (isset($_GET['add'])) {
    $id = $_GET['add'];

    $stmt = $db->prepare("SELECT * FROM products WHERE id=?");
    $stmt->execute([$id]);
    $p = $stmt->fetch();

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty'] += 1;
    } else {
        $_SESSION['cart'][$id] = $p;
        $_SESSION['cart'][$id]['qty'] = 1;
    }

    echo json_encode($_SESSION['cart']);
    exit;
}

// REMOVE
if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    echo json_encode($_SESSION['cart']);
    exit;
}

// UPDATE QTY
if (isset($_GET['update'])) {
    $id = $_GET['id'];
    $type = $_GET['type'];

    if ($type == "plus") {
        $_SESSION['cart'][$id]['qty']++;
    } else {
        $_SESSION['cart'][$id]['qty']--;
        if ($_SESSION['cart'][$id]['qty'] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }

    echo json_encode($_SESSION['cart']);
    exit;
}

$products = $db->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<div class="pos-layout">

    <!-- PRODUCTS -->
    <div class="product-area">
        <h1>Produk</h1>

        <div class="product-grid">
            <?php foreach ($products as $p): ?>
            <div class="product-card">
                <h3><?= $p['name'] ?></h3>
                <p>Rp <?= $p['price'] ?></p>

                <button class="btn btn-cart"
                        onclick="addToCart(<?= $p['id'] ?>)">
                    + Add
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- CART SIDEBAR -->
    <div class="cart-sidebar">
        <h2>Cart</h2>
        <div id="cartBox"></div>

        <h3 id="total">Total: 0</h3>

        <button class="btn-primary" onclick="checkout()">
            Checkout
        </button>
    </div>

</div>

<script>
let cart = {};

function addToCart(id) {
    fetch("?add=" + id)
    .then(res => res.json())
    .then(data => {
        cart = data;
        renderCart();
    });
}

function renderCart() {
    let html = "";
    let total = 0;

    for (let id in cart) {
        let item = cart[id];
        let subtotal = item.price * item.qty;
        total += subtotal;

        html += `
        <div class="cart-item">
            <p>${item.name}</p>
            <p>Rp ${item.price}</p>

            <button onclick="updateQty(${id},'minus')">-</button>
            ${item.qty}
            <button onclick="updateQty(${id},'plus')">+</button>

            <button onclick="removeItem(${id})">x</button>
        </div>
        `;
    }

    document.getElementById("cartBox").innerHTML = html;
    document.getElementById("total").innerText = "Total: Rp " + total;
}

function updateQty(id,type){
    fetch("?update=1&id="+id+"&type="+type)
    .then(res => res.json())
    .then(data => {
        cart = data;
        renderCart();
    });
}

function removeItem(id){
    fetch("?remove="+id)
    .then(res => res.json())
    .then(data => {
        cart = data;
        renderCart();
    });
}

function checkout(){
    alert("Checkout berhasil (nanti kita simpan DB)");
}
</script>

<?php
$content = ob_get_clean();
include '../layout.php';
?>