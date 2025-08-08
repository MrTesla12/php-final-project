<?php
// content/edit_content.php

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

// 3) Validate & fetch the content ID
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header('Location: content.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM content WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$post) {
    header('Location: content.php');
    exit;
}

// 4) Initialize form variables & errors
$errors = [];
$title = $post['title'];
$body  = $post['body'];

// 5) Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titleInput = trim($_POST['title'] ?? '');
    $bodyInput  = trim($_POST['body']  ?? '');

    // a) Validate
    if ($titleInput === '' || $bodyInput === '') {
        $errors[] = 'Both title and body are required.';
    }

    // b) Update if no errors
    if (empty($errors)) {
        $upd = $pdo->prepare(
            "UPDATE content SET title = ?, body = ? WHERE id = ?"
        );
        $upd->execute([$titleInput, $bodyInput, $id]);
        header('Location: content.php');
        exit;
    }

    // c) Repopulate on error
    $title = $titleInput;
    $body  = $bodyInput;
}

// 6) Include shared header template
require_once __DIR__ . '/../includes/header.php';
?>

<main class="container">
  <h1 class="my-4">Edit Content</h1>

  <!-- Show errors, if any -->
  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <ul>
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <!-- Edit form -->
  <form method="post">
    <div class="mb-3">
      <label for="title" class="form-label">Title</label>
      <input
        type="text" id="title" name="title"
        class="form-control"
        value="<?= htmlspecialchars($title) ?>"
        required>
    </div>
    <div class="mb-3">
      <label for="body" class="form-label">Body</label>
      <textarea
        id="body" name="body"
        class="form-control"
        rows="6"
        required><?= htmlspecialchars($body) ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Save Changes</button>
    <a href="content.php" class="btn btn-secondary">Cancel</a>
  </form>
</main>

<?php
// 7) Include shared footer template
require_once __DIR__ . '/../includes/footer.php';
?>
