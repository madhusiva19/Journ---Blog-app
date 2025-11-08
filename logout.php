<?php
require_once __DIR__ . '/config.php'; // Base config
require_once __DIR__ . '/auth.php';   // Auth helpers

session_start(); // Start session

// Clear session data
$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    // Remove session cookie
    setcookie(session_name(), '', time()-42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

session_destroy(); // End session

// Redirect to home
header('Location: ' . BASE_URL);
exit;
