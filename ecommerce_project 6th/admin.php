<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$stmt = $mysqli->prepare('SELECT is_admin FROM users WHERE id=? LIMIT 1');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$res = $stmt->get_result();
$u = $res->fetch_assoc();
if (empty($u) || !$u['is_admin']) {
    echo 'Access denied. You must be admin.';
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    if ($name === '' || $price <= 0) $errors[] = 'Name and positive price required.';
    if (!$errors) {
        $ins = $mysqli->prepare('INSERT INTO products (name, description, price, stock) VALUES (?, ?, ?, ?)');
        $ins->bind_param('ssdi', $name, $desc, $price, $stock);
        if ($ins->execute()) {
            $msg = 'Product added.';
        } else {
            $errors[] = 'Failed to add product.';
        }
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Admin - Add Product</title><link rel="stylesheet" href="styles.css"></head><body>
<?php include 'nav.php'; ?>
<div class="container"><h1>Admin - Add Product</h1>
<?php if ($errors): ?><div class="error"><?php echo implode('<br>',$errors); ?></div><?php endif; ?>
<?php if (!empty($msg)): ?><div class="success"><?php echo $msg; ?></div><?php endif; ?>
<form method="post">
  <label>Name<br><input name="name" required></label><br>
  <label>Description<br><textarea name="description"></textarea></label><br>
  <label>Price<br><input name="price" type="number" step="0.01" required></label><br>
  <label>Stock<br><input name="stock" type="number" required></label><br>
  <button type="submit">Add Product</button>
</form></div></body></html>