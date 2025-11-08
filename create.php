<?php
require_once __DIR__ . '/config.php';     // Base settings
require_once __DIR__ . '/db_connect.php'; // DB connection
require_once __DIR__ . '/auth.php';       // Login helpers

// Ensure session
if (session_status() === PHP_SESSION_NONE) session_start();

// Block if not logged in
if (!isLoggedIn()) {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>New Post</title>
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
  <h1>New Post</h1>

  <!-- Create post form -->
  <form class="form" method="post" action="create_post.php">

    <?php if (function_exists('csrf_field')) csrf_field(); ?>

    <label>Title
      <input type="text" name="title" required maxlength="200">
    </label>

    <!-- Markdown toolbar -->
    <div class="toolbar row mt-2">
      <button class="btn" type="button" onclick="mdBold()"><b>B</b></button>
      <button class="btn" type="button" onclick="mdItalic()"><i>I</i></button>
      <button class="btn" type="button" onclick="mdCode()">Code</button>
      <button class="btn" type="button" onclick="mdH1()">H1</button>
      <button class="btn" type="button" onclick="mdUL()">List</button>
      <button class="btn" type="button" onclick="mdQuote()">Quote</button>
    </div>

    <label>Content (Markdown supported)
      <textarea id="content" name="content" required></textarea>
    </label>

    <div class="row">
      <button class="btn primary" type="submit">Publish</button>
      <a class="btn" href="<?= BASE_URL ?>">Cancel</a>
    </div>

  </form>
</main>

</body>
</html>
