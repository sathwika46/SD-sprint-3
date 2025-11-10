<?php
session_start();
require 'db.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($email === '' || $password === '') {
        $errors[] = 'Enter email and password.';
    } else {
        $stmt = $mysqli->prepare('SELECT id, username, password_hash FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            if (password_verify($password, $row['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                header('Location: products.php');
                exit;
            } else {
                $errors[] = 'Incorrect password.';
            }
        } else {
            $errors[] = 'No user with that email.';
        }
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container">
  <h2>Login</h2>
  <?php if ($errors): ?>
    <div class="error"><?php echo implode('<br>', $errors); ?></div>
  <?php endif; ?>
  <form method="post" action="login.php">
    <label>Email<br><input name="email" type="email" required></label><br>
    <label>Password<br><input name="password" type="password" required></label><br>
    <button type="submit">Login</button>
  </form>
</div>
</body>
</html>