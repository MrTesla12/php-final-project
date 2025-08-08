<?php
// content/add_content.php

// 1) Include shared database connection
require_once __DIR__ . '/../includes/db.php';

// 2) Start session and protect page
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// 3) Initialize error array
$errors = [];

// 4) Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // a) Collect & trim inputs
    $title = trim($_POST['title'] ?? '');
    $body  = trim($_POST['body']  ?? '');

    // b) Validate required fields
    if ($title === '' || $body === '') {
        $errors[] = 'Both title and body are required.';
    }

    // c) If no errors, insert into database
    if (empty($errors)) {
        $stmt = $pdo->prepare(
            "INSERT INTO content (title, body) VALUES (?, ?)"
        );
        $stmt->execute([$title, $body]);
        header('Location: content.php');
        exit;
    }
}

// 5) Include shared header template
require_once __DIR__ . '/../includes/header.php';
?>

<main class="container">
  <h1 class="my-4">Add New Content</h1>

  <!-- Display validation errors -->
  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <ul>
        <?php foreach ($errors as $err): ?>
          <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <!-- Content creation form -->
  <form method="post">
    <!-- Title -->
    <div class="mb-3">
      <label for="title" class="form-label">Title</label>
      <input
        type="text" id="title" name="title"
        class="form-control"
        value="<?= htmlspecialchars($title ?? '') ?>"
        required>
    </div>

    <!-- Body -->
    <div class="mb-3">
      <label for="body" class="form-label">Body</label>
      <textarea
        id="body" name="body"
        class="form-control"
        rows="6"
        required><?= htmlspecialchars($body ?? '') ?></textarea>
    </div>

    <!-- Submit -->
    <button type="submit" class="btn btn-success">Publish</button>
    <a href="content.php" class="btn btn-secondary">Cancel</a>
  </form>
</main>

<?php
// 6) Include shared footer template
require_once __DIR__ . '/../includes/footer.php';
?>
