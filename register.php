<?php
/**
 * register.php — User Registration
 */
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

require 'db.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $username = trim(htmlspecialchars($_POST['username'] ?? ''));
    $email    = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm']  ?? '';

    // Validate
    if (empty($username) || strlen($username) < 3) {
        $errors[] = 'Username must be at least 3 characters.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        // Check duplicates
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);

        if ($stmt->rowCount() > 0) {
            $errors[] = 'Email or username already registered.';
        } else {
            // Hash and insert
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $ins  = $pdo->prepare(
                "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
            );
            $ins->execute([$username, $email, $hash]);

            $success = 'Account created! You can now login.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register | TradePro</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body class="auth-page">

<div class="auth-wrapper">
  <div class="auth-card">

    <a class="auth-logo" href="index.php">TRADE<span>PRO</span></a>
    <h2 class="auth-title">Create Account</h2>
    <p class="auth-sub">Start trading in minutes</p>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-error">
        <?php foreach ($errors as $e): ?>
          <p><?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="alert alert-success">
        <p><?= htmlspecialchars($success) ?></p>
        <a href="login.php" class="btn-auth-link">Go to Login</a>
      </div>
    <?php else: ?>

    <form method="POST" action="register.php" id="reg-form" novalidate>
      <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" placeholder="e.g. john_trader"
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required />
      </div>
      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" placeholder="you@email.com"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required />
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Min. 6 characters" required />
      </div>
      <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" name="confirm" placeholder="Repeat password" required />
      </div>
      <button type="submit" class="btn-submit">Create Account</button>
    </form>

    <p class="auth-switch">
      Already have an account? <a href="login.php">Login here</a>
    </p>

    <?php endif; ?>
  </div>

  <!-- Decorative chart lines in background -->
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
