<?php
session_start();
$_SESSION['user_id'] = 1; 

require 'db.php';

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
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['delete_id'])) {
        $del_id = (int)$_POST['delete_id'];
        $stmt = $mysqli->prepare('DELETE FROM products WHERE id=?');
        $stmt->bind_param('i', $del_id);
        if ($stmt->execute()) $msg = 'Product deleted successfully.';
        else $errors[] = 'Failed to delete product.';
    }

    else {
        $name = trim($_POST['name'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $stock = (int)($_POST['stock'] ?? 0);
        $image_path = '';

        if (!empty($_FILES['image']['name'])) {
            $target_dir = __DIR__ . "/uploads/";
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            $file_name = time() . "_" . basename($_FILES['image']['name']);
            $target_file = $target_dir . $file_name;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_path = "uploads/" . $file_name;
            } else {
                $errors[] = "Failed to upload image.";
            }
        } else {
            $image_path = $_POST['existing_image'] ?? '';
        }

        if ($name === '' || $price <= 0) $errors[] = 'Name and positive price required.';

        if (!$errors) {
            if (!empty($_POST['id'])) {
                $stmt = $mysqli->prepare('UPDATE products SET name=?, description=?, price=?, stock=?, image=? WHERE id=?');
                $stmt->bind_param('ssdisi', $name, $desc, $price, $stock, $image_path, $_POST['id']);
                $stmt->execute();
                $msg = 'Product updated successfully.';
            } else {
                $stmt = $mysqli->prepare('INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)');
                $stmt->bind_param('ssdii', $name, $desc, $price, $stock, $image_path);
                $stmt->execute();
                $msg = 'Product added successfully.';
            }
        }
    }
}

$res = $mysqli->query('SELECT * FROM products ORDER BY id DESC');
$products = $res->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin - Manage Products</title>
<link rel="stylesheet" href="styles.css">
<style>
.container { max-width: 900px; margin: 30px auto; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
th, td { border: 1px solid #ccc; padding: 8px; text-align: left; vertical-align: middle; }
th { background: #f2f2f2; }
img { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; }
button, input[type=submit] { cursor: pointer; }
.edit-btn { background: #0d6efd; color: white; border: none; padding: 6px 10px; border-radius: 4px; }
.delete-btn { background: #dc3545; color: white; border: none; padding: 6px 10px; border-radius: 4px; }
.success { background: #d4edda; color: #155724; padding: 8px; margin: 10px 0; border-radius: 4px; }
.error { background: #f8d7da; color: #721c24; padding: 8px; margin: 10px 0; border-radius: 4px; }
</style>
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container">
  <h1>Admin - Manage Products</h1>

  <?php if ($errors): ?><div class="error"><?= implode('<br>', $errors) ?></div><?php endif; ?>
  <?php if ($msg): ?><div class="success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

  <h2>Add / Edit Product</h2>
  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" id="id">
    <input type="hidden" name="existing_image" id="existing_image">

    <label>Name<br><input name="name" id="name" required></label><br>
    <label>Description<br><textarea name="description" id="description"></textarea></label><br>
    <label>Price<br><input name="price" id="price" type="number" step="0.01" required></label><br>
    <label>Stock<br><input name="stock" id="stock" type="number" required></label><br>
    <label>Image<br><input type="file" name="image"></label><br><br>

    <button type="submit">Save Product</button>
  </form>

  <h2>Existing Products</h2>
  <table>
    <tr><th>ID</th><th>Image</th><th>Name</th><th>Price</th><th>Stock</th><th>Action</th></tr>
    <?php foreach ($products as $p): ?>
      <tr>
        <td><?= (int)$p['id'] ?></td>
        <td><?= $p['image'] ? '<img src="'.$p['image'].'">' : '—' ?></td>
        <td><?= htmlspecialchars($p['name']) ?></td>
        <td>$<?= number_format($p['price'], 2) ?></td>
        <td><?= (int)$p['stock'] ?></td>
        <td>
          <button class="edit-btn" onclick='editProduct(<?= json_encode($p) ?>)'>Edit</button>
          <form method="post" style="display:inline" onsubmit="return confirm('Delete this product?');">
            <input type="hidden" name="delete_id" value="<?= (int)$p['id'] ?>">
            <button type="submit" class="delete-btn">Delete</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>

<script>
function editProduct(p) {
  document.getElementById('id').value = p.id;
  document.getElementById('name').value = p.name;
  document.getElementById('description').value = p.description;
  document.getElementById('price').value = p.price;
  document.getElementById('stock').value = p.stock;
  document.getElementById('existing_image').value = p.image;
  window.scrollTo({top: 0, behavior: 'smooth'});
}
</script>
</body>
</html>
