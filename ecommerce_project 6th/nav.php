

<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'db.php';

$user = null;
$is_admin = false;

if (isset($_SESSION['user_id'])) {
    $stmt = $mysqli->prepare("SELECT username, is_admin FROM users WHERE id=?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    if ($user) $is_admin = (bool)$user['is_admin'];
}
?>
<nav style="background:#222; color:white; padding:10px;">
  <a href="index.php" style="color:white; margin-right:15px;">Home</a>
  <a href="products.php" style="color:white; margin-right:15px;">Products</a>
  <a href="cart.php" style="color:white; margin-right:15px;">Cart</a>
  <?php if ($is_admin): ?>
    <a href="admin_products.php" style="color:yellow; margin-right:15px;">Admin Panel</a>
  <?php endif; ?>
  <span style="float:right;">
    <?php if ($user): ?>
      Logged in as <strong><?= htmlspecialchars($user['username']) ?></strong> |
      <a href="logout.php" style="color:white;">Logout</a>
    <?php else: ?>
      <a href="login.php" style="color:white;">Login</a>
            <a href="register.php" style="color:white;">Register</a>

    <?php endif; ?>
  </span>
</nav>
