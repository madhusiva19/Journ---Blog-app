<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/auth.php';

// Check login
if (!isLoggedIn()) {
    http_response_code(403);
    die('Login first');
}

// Get post ID
$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    die('Invalid request');
}

// Fetch post owner
$stmt = $conn->prepare("SELECT user_id FROM blogPost WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($ownerId);
$stmt->fetch();
$stmt->close();

// Check ownership
$currentUserId = $_SESSION['user']['id'] ?? 0;
if (!$ownerId || (int)$ownerId !== (int)$currentUserId) {
    http_response_code(403);
    die('Not authorized');
}

// Delete post
$del = $conn->prepare("DELETE FROM blogPost WHERE id = ?");
$del->bind_param("i", $id);
$del->execute();
$del->close();

// Redirect
header("Location: " . BASE_URL);
exit;
