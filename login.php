<?php
/**
 * login.php — User Login
 */
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

require 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (empty($password)) {
        $errors[] = 'Please enter your password.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: dashboard.php');
            exit;
        }

        $errors[] = 'Invalid email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | TradePro</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body class="auth-page">

<div class="auth-wrapper">
  <div class="auth-card">

    <a class="auth-logo" href="index.php">TRADE<span>PRO</span></a>
    <h2 class="auth-title">Welcome Back</h2>
    <p class="auth-sub">Login to continue trading</p>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-error">
        <?php foreach ($errors as $e): ?>
          <p><?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="login.php" id="login-form" novalidate>
      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" placeholder="you@email.com"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required />
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Enter your password" required />
      </div>
      <button type="submit" class="btn-submit">Login</button>
    </form>

    <p class="auth-switch">
      Don&apos;t have an account? <a href="register.php">Register here</a>
    </p>
  </div>

  <div class="auth-bg-lines">
    <svg viewBox="0 0 800 400" preserveAspectRatio="none">
      <polyline points="0,300 100,250 200,280 300,180 400,220 500,150 600,170 700,100 800,130"
                fill="none" stroke="rgba(26,140,255,0.12)" stroke-width="2"/>
      <polyline points="0,350 100,320 200,340 300,260 400,290 500,210 600,240 700,180 800,200"
                fill="none" stroke="rgba(0,196,140,0.08)" stroke-width="2"/>
    </svg>
  </div>
</div>

<script src="script.js"></script>
</body>
</html>
