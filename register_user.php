<?php
// Show all errors temporarily for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db_connect.php'; // $conn
require_once __DIR__ . '/auth.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// ---- CSRF check ----
if (function_exists('verify_csrf')) {
    verify_csrf();
}

// ---- Get POST data ----
$username = trim($_POST['username'] ?? '');
$email    = strtolower(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';

if ($username === '' || $email === '' || $password === '') {
    die('All fields are required.');
}

// ---- Make sure the user table exists ----
$table_check = $conn->query("SHOW TABLES LIKE 'user'");
if ($table_check->num_rows === 0) {
    die('User table does not exist in the database.');
}

// ---- Check if email already exists (case-insensitive) ----
$stmt = $conn->prepare("SELECT id FROM user WHERE LOWER(email) = LOWER(?)");
if (!$stmt) die('Prepare failed: ' . $conn->error);

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->fetch_assoc()) {
    $stmt->close();
    die('Email already registered.');
}
$stmt->close();

// ---- Insert new user ----
$hash = password_hash($password, PASSWORD_DEFAULT);
$ins = $conn->prepare("INSERT INTO user (username, email, password) VALUES (?, ?, ?)");
if (!$ins) die('Prepare failed: ' . $conn->error);

$ins->bind_param("sss", $username, $email, $hash);
$ins->execute();
$ins->close();

// ---- Redirect to login page ----
header('Location: ' . BASE_URL . 'login.php');
exit;
