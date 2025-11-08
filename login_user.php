<?php
require_once __DIR__ . '/db_connect.php'; // DB connection
require_once __DIR__ . '/auth.php';       // Login helpers
require_once __DIR__ . '/config.php';     // Base settings
verify_csrf();                            // CSRF check

$email = trim($_POST['email'] ?? '');
$pass  = $_POST['password'] ?? '';

// Fetch user by email
$stmt = $conn->prepare("SELECT id, username, email, password FROM user WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$u = $result->fetch_assoc();
$stmt->close();

// Validate credentials
if (!$u || !password_verify($pass, $u['password'])) {
    die('Invalid credentials.');
}

// Store session
$_SESSION['user'] = [
    'id'       => $u['id'],
    'username' => $u['username'],
    'email'    => $u['email']
];

// Redirect to home
header('Location: ' . BASE_URL);
exit;
