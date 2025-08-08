<?php
// users.php

// 1) Include the database connection
require_once __DIR__ . '/includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2) Protect this page: only logged-in admins can see it
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// 3) Fetch all admin users from the database
try {
    $stmt = $pdo->query("SELECT * FROM admins ORDER BY id ASC");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error fetching users: " . htmlspecialchars($e->getMessage()));
}

// 4) Include the global header (logo, nav, logout)
require_once __DIR__ . '/includes/header.php';
?>

<main class="container">
  <h1 class="my-4">Manage Administrators</h1>

  <?php if (empty($admins)): ?>
    <p>No admin users found.</p>
  <?php else: ?>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Avatar</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($admins as $user): ?>
          <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td>
              <?php if ($user['profile_image']): ?>
                <img
                  src="uploads/<?= htmlspecialchars($user['profile_image']) ?>"
                  alt="Avatar"
                  width="50"
                  class="rounded-circle"
                >
              <?php endif; ?>
            </td>
            <td>
              <a
                href="edit_user.php?id=<?= $user['id'] ?>"
                class="btn btn-sm btn-outline-primary"
              >Edit</a>
              <a
                href="delete_user.php?id=<?= $user['id'] ?>"
                class="btn btn-sm btn-outline-danger"
                onclick="return confirm('Delete this user?');"
              >Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</main>

<?php
// 5) Include the global footer
require_once __DIR__ . '/includes/footer.php';
