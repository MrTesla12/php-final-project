<?php
// login.php

// 1) Include PDO connection 
require_once __DIR__ . '/includes/db.php';

// 2) Start session 
session_start();

$errors = [];

// 3) Handle POST submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // a) Collect & trim inputs
    $email    = trim($_POST['email']    ?? '');
    $password =            $_POST['password'] ?? '';

    // b) Validate required fields
    if ($email === '' || $password === '') {
        $errors[] = 'Email and password are required.';
    } else {
        // c) Look up user by email
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // d) Verify password and log in
        if ($user && password_verify($password, $user['password'])) {
            // i) Save user info in session
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            // ii) Redirect to home page
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Invalid email or password.';
        }
    }
}

// 4) Include header (will show nav + login form state)
require_once __DIR__ . '/includes/header.php';
?>

<main class="container">
  <h1 class="my-4">Login</h1>

  <!-- Display any errors -->
  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <ul>
        <?php foreach ($errors as $err): ?>
          <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <!-- Login form -->
  <form method="post">
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input
        type="email"
        id="email"
        name="email"
        class="form-control"
        value="<?= htmlspecialchars($email ?? '') ?>"
        required>
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input
        type="password"
        id="password"
        name="password"
        class="form-control"
        required>
    </div>

    <button type="submit" class="btn btn-primary">Login</button>
  </form>
</main>

<?php
// 5) Include footer
require_once __DIR__ . '/includes/footer.php';
?>
