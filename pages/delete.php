<?php
require '../config/db.php';

$id = $_GET['id'];

$stmt = $db->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$id]);

header("Location: products.php");
exit;
?>