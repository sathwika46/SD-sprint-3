<?php
session_start();
require 'db.php';

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product_id'])) {
    $pid = (int)$_POST['add_product_id'];
    $qty = max(1, (int)$_POST['qty']);
    if (!isset($_SESSION['cart'][$pid])) $_SESSION['cart'][$pid] = 0;
    $_SESSION['cart'][$pid] += $qty;
    $msg = "Product added to cart!";
}

$res = $mysqli->query("SELECT * FROM products ORDER BY id DESC");
$products = $res->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Our Products</title>
<link rel="stylesheet" href="styles.css">
<style>
body {
  font-family: Arial, sans-serif;
  background: #f8f9fa;
  margin: 0;
}
.container {
  max-width: 1100px;
  margin: 40px auto;
  padding: 20px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
h1 {
  text-align: left;
  font-size: 1.8rem;
  margin-bottom: 25px;
  color: #222;
}
.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
  gap: 28px;
}
.product-card {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 3px 8px rgba(0,0,0,0.08);
  text-align: center;
  padding: 18px;
  transition: all 0.25s ease;
}
.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 14px rgba(0,0,0,0.12);
}
.product-card img {
  max-width: 100%;
  height: 180px;
  object-fit: contain;
  border-radius: 8px;
  margin-bottom: 10px;
}
.product-card h3 {
  font-size: 1.1rem;
  color: #222;
  margin: 10px 0 6px;
}
.product-card p {
  color: #666;
  font-size: 0.9rem;
  min-height: 40px;
}
.price {
  font-weight: bold;
  color: #007bff;
  margin: 10px 0;
}
button {
  background: #007bff;
  color: white;
  border: none;
  border-radius: 6px;
  padding: 8px 14px;
  cursor: pointer;
  font-size: 0.9rem;
  transition: background 0.2s;
}
button:hover {
  background: #0056b3;
}
form {
  margin-top: 8px;
}
.success {
  background: #d4edda;
  color: #155724;
  padding: 8px 12px;
  border-radius: 4px;
  margin-bottom: 15px;
  display: inline-block;
}
</style>
</head>
<body>

<div class="container">
  <h1>Our Products</h1>

  <?php if (!empty($msg)): ?>
    <div class="success"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <div class="products-grid">
    <?php foreach ($products as $p): 
      $img = (!empty($p['image']) && file_exists($p['image'])) ? $p['image'] : 'uploads/no-image.png';
    ?>
    <div class="product-card">
      <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
      <h3><?= htmlspecialchars($p['name']) ?></h3>
      <p><?= htmlspecialchars($p['description']) ?></p>
      <div class="price">$<?= number_format($p['price'], 2) ?></div>

      <form method="post">
        <input type="hidden" name="add_product_id" value="<?= (int)$p['id'] ?>">
        <input type="number" name="qty" value="1" min="1" style="width:60px">
        <button type="submit" <?= $p['stock'] <= 0 ? 'disabled' : '' ?>>
          <?= $p['stock'] > 0 ? 'Add to Cart' : 'Out of Stock' ?>
        </button>
      </form>
    </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>
