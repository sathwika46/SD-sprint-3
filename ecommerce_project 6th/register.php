<?php
session_start();
require 'db.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($username === '' || $email === '' || $password === '') {
        $errors[] = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email.';
    } elseif ($password !== $password2) {
        $errors[] = 'Passwords do not match.';
    } else {
        $stmt = $mysqli->prepare('SELECT id FROM users WHERE email = ? OR username = ? LIMIT 1');
        $stmt->bind_param('ss', $email, $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = 'Email or username already exists.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $mysqli->prepare('INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)');
            $ins->bind_param('sss', $username, $email, $hash);
            if ($ins->execute()) {
                $_SESSION['user_id'] = $ins->insert_id;
                $_SESSION['username'] = $username;
                header('Location: products.php?msg=registered');
                exit;
            } else {
                $errors[] = 'Registration failed.';
            }
        }
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Register</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container">
  <h2>Register</h2>
  <?php if ($errors): ?>
    <div class="error"><?php echo implode('<br>', $errors); ?></div>
  <?php endif; ?>
  <form method="post" action="register.php">
    <label>Username<br><input name="username" required></label><br>
    <label>Email<br><input name="email" type="email" required></label><br>
    <label>Password<br><input name="password" type="password" required></label><br>
    <label>Confirm Password<br><input name="password2" type="password" required></label><br>
    <button type="submit">Register</button>
  </form>
</div>
</body>
</html>