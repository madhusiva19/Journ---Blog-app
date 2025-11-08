<?php
require_once __DIR__.'/config.php';
require_once __DIR__.'/db_connect.php'; // $conn
require_once __DIR__.'/auth.php';

// Enable error display for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Placeholder for CSRF field (replace with real CSRF if you implement it)
function csrf_field() {
    echo '<input type="hidden" name="csrf_token" value="dummy">';
}

// Check login
if (!isset($_SESSION['user'])) {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}
$currentUserId = $_SESSION['user']['id'] ?? 0;

// Get post ID
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    die('Invalid post ID');
}

// Fetch post
$stmt = $conn->prepare("SELECT * FROM blogPost WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();

if (!$post) {
    http_response_code(404);
    die('Post not found');
}

// Only owner can edit
if ((int)$post['user_id'] !== (int)$currentUserId) {
    http_response_code(403);
    die('Not authorized');
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Edit Post</title>
<link rel="stylesheet" href="<?= BASE_URL ?>styles.css">
<script src="<?= BASE_URL ?>app.js" defer></script>
</head>
<body>

<header class="site-header">
  <div class="container nav">
    <a class="brand" href="<?= BASE_URL ?>">My Blog</a>
    <nav>
      <a href="<?= BASE_URL ?>">Home</a>
      <a class="btn" href="<?= BASE_URL ?>logout.php">Logout</a>
    </nav>
  </div>
</header>

<main class="container">
  <h1>Edit Post</h1>
  <form class="form" method="post" action="update_post.php">
    <?php csrf_field(); ?>
    <input type="hidden" name="id" value="<?= (int)$post['id'] ?>">

    <label>Title
      <input type="text" name="title" required maxlength="200" value="<?= htmlspecialchars($post['title']) ?>">
    </label>

    <!-- Toolbar -->
    <div class="toolbar row mt-2">
      <button class="btn" type="button" onclick="mdBold()"><b>B</b></button>
      <button class="btn" type="button" onclick="mdItalic()"><i>I</i></button>
      <button class="btn" type="button" onclick="mdCode()">Code</button>
      <button class="btn" type="button" onclick="mdH1()">H1</button>
      <button class="btn" type="button" onclick="mdUL()">List</button>
      <button class="btn" type="button" onclick="mdQuote()">Quote</button>
    </div>

    <label>Content (Markdown supported)
      <textarea id="content" name="content" required><?= htmlspecialchars($post['content']) ?></textarea>
    </label>

    <div class="row">
      <button class="btn primary" type="submit">Save Changes</button>
      <a class="btn" href="<?= BASE_URL ?>post.php?id=<?= (int)$post['id'] ?>">Cancel</a>
    </div>
  </form>
</main>

</body>
</html>
