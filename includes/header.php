<?php
// includes/header.php
// Start session if none exists
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My PHP Site</title>
  <!-- Bootstrap CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-..."
    crossorigin="anonymous"
  >
  <!-- Main stylesheet -->
  <link rel="stylesheet" href="/php-final-project/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
      <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="/php-final-project/index.php">MyLogo</a>
        <!-- Toggler/collapsibe Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Navbar links and forms -->
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="/php-final-project/index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/php-final-project/content/content.php">Content</a>
            </li>

            <?php if (!isset($_SESSION['user_id'])): ?>
              <!-- When not logged in -->
              <li class="nav-item">
                <a class="nav-link" href="/php-final-project/register.php">Register</a>
              </li>
            <?php else: ?>
              <!-- When logged in: show New Content + Manage Users -->
              <li class="nav-item">
                <a class="nav-link" href="/php-final-project/content/add_content.php">New Content</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/php-final-project/users.php">Manage Users</a>
              </li>
            <?php endif; ?>
          </ul>

          <!-- Login form or Logout button -->
          <?php if (!isset($_SESSION['user_id'])): ?>
            <form class="d-flex" method="post" action="/php-final-project/login.php">
              <input class="form-control me-2" type="email" name="email" placeholder="Email" required>
              <input class="form-control me-2" type="password" name="password" placeholder="Password" required>
              <button class="btn btn-outline-success" type="submit">Login</button>
            </form>
          <?php else: ?>
            <span class="navbar-text me-3">
              Hello, <?= htmlspecialchars($_SESSION['user_name']) ?>
            </span>
            <a class="btn btn-outline-danger" href="/php-final-project/logout.php">Logout</a>
          <?php endif; ?>
        </div>
      </div>
    </nav>
  </header>
