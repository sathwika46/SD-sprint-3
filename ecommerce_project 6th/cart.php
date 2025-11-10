<?php
session_start();
require 'db.php';

$cart = $_SESSION['cart'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['clear'])) {
        unset($_SESSION['cart']);
        header('Location: cart.php');
        exit;
    } elseif (isset($_POST['remove'])) {
        $rid = (int)$_POST['remove'];
        unset($_SESSION['cart'][$rid]);
        header('Location: cart.php');
        exit;
    } elseif (isset($_POST['checkout'])) {
        if (!isset($_SESSION['user_id'])) {
            $error = 'You must be logged in to checkout.';
        } elseif (empty($cart)) {
            $error = 'Cart is empty.';
        } else {
            $ids = array_keys($cart);
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $types = str_repeat('i', count($ids));
            $stmt = $mysqli->prepare("SELECT id, price FROM products WHERE id IN ($placeholders)");
            $stmt->bind_param($types, ...$ids);
            $stmt->execute();
            $res = $stmt->get_result();
            $total = 0;
            $prices = [];
            while ($r = $res->fetch_assoc()) {
                $prices[$r['id']] = $r['price'];
            }
            foreach ($cart as $pid => $qty) {
                $total += ($prices[$pid] ?? 0) * $qty;
            }
            $billing_name = $mysqli->real_escape_string($_POST['billing_name'] ?? '');
            $billing_email = $mysqli->real_escape_string($_POST['billing_email'] ?? '');
            $ins = $mysqli->prepare('INSERT INTO orders (user_id, total, billing_name, billing_email) VALUES (?, ?, ?, ?)');
            $uid = $_SESSION['user_id'];
            $ins->bind_param('idss', $uid, $total, $billing_name, $billing_email);
            if ($ins->execute()) {
                $order_id = $ins->insert_id;
                $it = $mysqli->prepare('INSERT INTO order_items (order_id, product_id, qty, price) VALUES (?, ?, ?, ?)');
                foreach ($cart as $pid => $qty) {
                    $price = $prices[$pid] ?? 0;
                    $it->bind_param('iiid', $order_id, $pid, $qty, $price);
                    $it->execute();
                }
                unset($_SESSION['cart']);
                $success = 'Order placed successfully. (Simulated)';
            } else {
                $error = 'Failed to place order.';
            }
        }
    }
}

$items = [];
if ($cart) {
    $ids = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));
    $stmt = $mysqli->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc()) {
        $r['qty'] = $cart[$r['id']];
        $r['line'] = $r['qty'] * $r['price'];
        $items[] = $r;
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Cart</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container">
  <h1>Your Cart</h1>
  <?php if (!empty($error)): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
  <?php if (!empty($success)): ?><div class="success"><?php echo $success; ?></div><?php endif; ?>
  <?php if (empty($items)): ?>
    <p>Your cart is empty.</p>
  <?php else: ?>
    <table class="cart-table">
      <tr><th>Product</th><th>Qty</th><th>Price</th><th>Line</th><th>Action</th></tr>
      <?php $total=0; foreach($items as $it): $total += $it['line']; ?>
        <tr>
          <td><?php echo htmlspecialchars($it['name']); ?></td>
          <td><?php echo (int)$it['qty']; ?></td>
          <td>$<?php echo number_format($it['price'],2); ?></td>
          <td>$<?php echo number_format($it['line'],2); ?></td>
          <td>
            <form method="post" style="display:inline">
              <button name="remove" value="<?php echo (int)$it['id']; ?>">Remove</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <tr><td colspan="3" style="text-align:right"><strong>Total:</strong></td><td>$<?php echo number_format($total,2); ?></td><td></td></tr>
    </table>
    <form method="post" class="checkout-form">
      <h3>Billing Information</h3>
      <label>Name<br><input name="billing_name" required></label><br>
      <label>Email<br><input name="billing_email" type="email" required></label><br>
      <button name="checkout" type="submit">Checkout (Simulate)</button>
    </form>
    <form method="post"><button name="clear" type="submit">Clear Cart</button></form>
  <?php endif; ?>
</div>
</body>
</html>