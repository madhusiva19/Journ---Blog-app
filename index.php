<?php
// Show errors (dev only)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load core files
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/Parsedown.php';
$Parsedown = new Parsedown();

// Get latest posts
$sql = "
    SELECT b.id, b.title, LEFT(b.content,200) AS snippet, b.created_at, u.username
    FROM blogPost b 
    JOIN user u ON u.id = b.user_id
    ORDER BY b.created_at DESC
";
$result = $conn->query($sql);
$rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="<?= BASE_URL ?>styles.css">
<title>JOURN</title>
</head>
<body>

<header class="site-header">
  <div class="container nav">
    <a class="brand" href="<?= BASE_URL ?>">JOURN</a>
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
  <h1>Latest Posts</h1>

  <div class="grid">
    <?php foreach ($rows as $r): ?>
      <article class="card">
        <h2><a href="post.php?id=<?= (int)$r['id'] ?>"><?= htmlspecialchars($r['title']) ?></a></h2>

        <p class="meta">
          By <?= htmlspecialchars($r['username']) ?> • <?= htmlspecialchars($r['created_at']) ?>
        </p>

        <div class="snippet">
          <?= $Parsedown->text($r['snippet']) ?>...
        </div>

        <a class="btn mt-2" href="post.php?id=<?= (int)$r['id'] ?>">Read</a>
      </article>
    <?php endforeach; ?>
  </div>
</main>

</body>
</html>
