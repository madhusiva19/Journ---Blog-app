<?php
require_once __DIR__ . '/config.php';     // Base config
require_once __DIR__ . '/db_connect.php'; // DB connection
require_once __DIR__ . '/auth.php';       // Auth helpers

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Find user
    $stmt = $conn->prepare("SELECT id, username, password FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    // Check password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id'       => $user['id'],
            'username' => $user['username']
        ];
        header("Location: index.php"); // Redirect
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
<link rel="stylesheet" href="<?= BASE_URL ?>styles.css">
</head>
<body>

<div class="container form-card">
<h1>Login</h1>

<?php if ($error): ?>
<p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST">
    <label>Username</label>
    <input type="text" name="username" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button class="btn primary" type="submit">Login</button>
</form>

<p class="mt-2">Don't have an account? <a href="register.php">Register here</a>.</p>

</div>

</body>
</html>
