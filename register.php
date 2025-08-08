<?php
// register.php

// 1) Include the database connection
require_once __DIR__ . '/includes/db.php';

// 2) Include header (starts session and outputs <head> and navbar)
require_once __DIR__ . '/includes/header.php';

// 3) Initialize error array
$errors = [];

// 4) Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // a) Collect & trim inputs
    $name     = trim($_POST['name']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password =            $_POST['password'] ?? '';

    // b) Validate required fields
    if ($name === '' || $email === '' || $password === '') {
        $errors[] = 'Name, email, and password are required.';
    }

    // c) Validate email format
    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    // d) Check for duplicate email
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'That email is already registered.';
        }
    }

    // e) Handle profile-image upload with file-type validation
    $imgName = null;
    if (empty($errors) 
        && !empty($_FILES['profile_image']['name']) 
        && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK
    ) {
        $allowed = ['jpg','jpeg','png','gif'];
        $ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = 'Invalid image type. Only JPG, JPEG, PNG & GIF allowed.';
        } else {
            $imgName = uniqid('img_') . ".{$ext}";
            move_uploaded_file(
                $_FILES['profile_image']['tmp_name'], 
                __DIR__ . "/uploads/{$imgName}"
            );
        }
    }

    // f) If no errors, hash password & insert user
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $ins = $pdo->prepare(
            "INSERT INTO admins (name, email, password, profile_image) VALUES (?, ?, ?, ?)"
        );
        $ins->execute([$name, $email, $hash, $imgName]);

        // Redirect to home
        header('Location: index.php');
        exit;
    }
}
?>

<main class="container">
  <h1 class="my-4">Register</h1>

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

  <!-- Registration form -->
  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="name" class="form-label">Name</label>
      <input type="text" id="name" name="name" class="form-control" 
             value="<?= htmlspecialchars($name ?? '') ?>" required>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" id="email" name="email" class="form-control" 
             value="<?= htmlspecialchars($email ?? '') ?>" required>
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" id="password" name="password" 
             class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="profile_image" class="form-label">Profile Image</label>
      <input type="file" id="profile_image" name="profile_image" 
             class="form-control" accept="image/*">
    </div>

    <button type="submit" class="btn btn-primary">Register</button>
  </form>
</main>

<?php
// 5) Include footer
require_once __DIR__ . '/includes/footer.php';
?>
