<?php
require 'config/db.php';

$db->exec("DROP TABLE IF EXISTS products");
$db->exec("DROP TABLE IF EXISTS transactions");
$db->exec("DROP TABLE IF EXISTS transaction_items");

$db->exec("CREATE TABLE products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    price INTEGER,
    stock INTEGER
)");

$db->exec("CREATE TABLE transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    total INTEGER,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$db->exec("CREATE TABLE transaction_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    transaction_id INTEGER,
    product_id INTEGER,
    qty INTEGER,
    price INTEGER
)");

echo "DB siap POS PRO";
?>