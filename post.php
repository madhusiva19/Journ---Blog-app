<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db_connect.php'; // $conn
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/Parsedown.php';

if (!class_exists('Parsedown')) {
    http_response_code(500);
    die('Parsedown not loaded. Put Parsedown.php in includes and check the require path.');
}

// Start session if not started
if (session_status() === PHP_SESSION_NONE) session_start();

// Get post ID
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    die('Invalid post ID.');
}

// Fetch post from database
$stmt = $conn->prepare("SELECT b.id, b.user_id, b.title, b.content, b.created_at, u.username
                        FROM blogPost b
                        JOIN user u ON u.id = b.user_id
                        WHERE b.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();

if (!$post) {
    http_response_code(404);
    die('Post not found');
}

// Parsedown for Markdown
$pd = new Parsedown();
$pd->setSafeMode(true);
$rendered = $pd->text($post['content']);

// Helper to check login
function isLoggedIn() {
    return isset($_SESSION['user']['id']);
}

// Helper for CSRF field
function csrf_field() {
    if (!isset($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    echo '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf'] . '">';
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?= BASE_URL ?>styles.css">
<title><?= htmlspecialchars($post['title']) ?></title>
</head>
<body>

<header class="site-header">
  <div class="container nav">
    <a class="brand" href="<?= BASE_URL ?>">My Blog</a>
    <nav>
      <a href="<?= BASE_URL ?>">Home</a>
      <?php if (isLoggedIn()): ?>
        <a class="btn primary" href="<?= BASE_URL ?>create.php">New Post</a>
        <span class="muted">Hi, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
        <a class="btn" href="<?= BASE_URL ?>logout.php">Logout</a>
      <?php else: ?>
        <a class="btn" href="<?= BASE_URL ?>register.php">Register</a>
        <a class="btn primary" href="<?= BASE_URL ?>login.php">Login</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<main class="container">
  <article class="prose card">
    <h1><?= htmlspecialchars($post['title']) ?></h1>
    <p class="meta">By <?= htmlspecialchars($post['username']) ?> • <?= htmlspecialchars($post['created_at']) ?></p>

    <!-- Rendered Markdown -->
    <div><?= $rendered ?></div>

    <!-- Edit/Delete for post owner only -->
    <?php if (isLoggedIn() && (int)$_SESSION['user']['id'] === (int)$post['user_id']): ?>
      <div class="row mt-3">
        <a class="btn" href="<?= BASE_URL ?>edit.php?id=<?= (int)$post['id'] ?>">Edit</a>
        <form class="mt-2" method="post" action="delete_post.php" onsubmit="return confirm('Delete this post?')">
          <?php csrf_field(); ?>
          <input type="hidden" name="id" value="<?= (int)$post['id'] ?>">
          <button class="btn danger">Delete</button>
        </form>
      </div>
    <?php endif; ?>
  </article>
</main>

</body>
</html>
