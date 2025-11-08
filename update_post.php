<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db_connect.php'; // MySQLi
require_once __DIR__ . '/auth.php';

// Check login
if (!isLoggedIn()) {
    http_response_code(403);
    die('Login first');
}

// Verify CSRF token
if (function_exists('verify_csrf')) {
    verify_csrf();
}

// Get POST data
$id      = (int)($_POST['id'] ?? 0);
$title   = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');

if ($id <= 0 || $title === '' || $content === '') {
    die('All fields are required.');
}

// Fetch the post to check ownership
$stmt = $conn->prepare("SELECT user_id FROM blogPost WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($ownerId);
$stmt->fetch();
$stmt->close();

$currentUserId = $_SESSION['user']['id'] ?? 0;
if (!$ownerId || (int)$ownerId !== (int)$currentUserId) {
    http_response_code(403);
    die('Not authorized');
}

// Update the post
$upd = $conn->prepare("UPDATE blogPost SET title = ?, content = ? WHERE id = ?");
$upd->bind_param("ssi", $title, $content, $id);
$upd->execute();
$upd->close();

// Redirect back to the updated post
header("Location: " . BASE_URL . "post.php?id=" . $id);
exit;
