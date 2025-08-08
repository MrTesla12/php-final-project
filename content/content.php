<?php
// content/content.php

// 1) Include the shared database connection
require_once __DIR__ . '/../includes/db.php';

// 2) Start session (header will also ensure this)
require_once __DIR__ . '/../includes/header.php';

?>

<main class="container">
  <h1 class="my-4">Website Content</h1>

  <?php
  // 3) Fetch all content entries, newest first
  try {
      $stmt = $pdo->query("SELECT * FROM content ORDER BY created_at DESC");
      $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (Exception $e) {
      echo "<div class='alert alert-danger'>Error loading content: "
           . htmlspecialchars($e->getMessage()) . "</div>";
      $posts = [];
  }

  // 4) Display posts or a “no content” message
  if (empty($posts)) {
      echo "<p>No content available yet. Check back later!</p>";
  } else {
      foreach ($posts as $post):
  ?>
    <article class="mb-5">
      <header>
        <h2><?= htmlspecialchars($post['title']) ?></h2>
        <small class="text-muted">
          Posted on <?= date('F j, Y \a\t g:i A', strtotime($post['created_at'])) ?>
        </small>
      </header>
      <section class="mt-2">
        <p><?= nl2br(htmlspecialchars($post['body'])) ?></p>
      </section>
      <?php if (isset($_SESSION['user_id'])): ?>
        <footer class="mt-2">
          <a
            href="edit_content.php?id=<?= $post['id'] ?>"
            class="btn btn-sm btn-outline-primary"
          >Edit</a>
          <a
            href="delete_content.php?id=<?= $post['id'] ?>"
            class="btn btn-sm btn-outline-danger"
            onclick="return confirm('Are you sure you want to delete this content?');"
          >Delete</a>
        </footer>
      <?php endif; ?>
    </article>
  <?php
      endforeach;
  }
  ?>
</main>

<?php
// 5) Include the shared footer
require_once __DIR__ . '/../includes/footer.php';
