<?php
require_once __DIR__ . '/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Register</title>
<link rel="stylesheet" href="<?= BASE_URL ?>styles.css">
</head>
<body>

<div class="container form-card">
<h1>Create Account</h1>

<form method="POST" action="register_user.php">
    <!-- CSRF token -->
    <?php
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    ?>
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <label>Username</label>
    <input type="text" name="username" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button class="btn primary" type="submit">Register</button>
</form>

<p class="mt-2">Already have an account? <a href="login.php">Login here</a>.</p>

</div>
</body>
</html>
