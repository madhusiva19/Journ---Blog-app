<?php
require_once __DIR__ . '/db_connect.php'; // DB connection
require_once __DIR__ . '/auth.php';      // Login check
require_once __DIR__ . '/config.php';    // Base URL etc.

if (!isLoggedIn()) {          // Block access if not logged in
    http_response_code(403);
    die('Login first');
}

$title   = trim($_POST['title'] ?? '');   // Get title
$content = trim($_POST['content'] ?? ''); // Get content

if ($title === '' || $content === '') {   // Simple validation
    die('Title & content required.');
}

$user_id = $_SESSION['user']['id'];       // Current user

// Insert post
$stmt = $conn->prepare("INSERT INTO blogPost (user_id, title, content) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $title, $content);
$stmt->execute();
$stmt->close();

header("Location: " . BASE_URL); // Redirect to home
exit;
?>
