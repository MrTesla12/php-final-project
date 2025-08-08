<?php
// edit_user.php

// 1) Include the database connection
require_once __DIR__ . '/includes/db.php';

// 2) Start session if needed and protect page
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// 3) Validate the ID
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header('Location: users.php');
    exit;
}

// 4) Fetch existing user
$stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    header('Location: users.php');
    exit;
}

// 5) Initialize form values and errors
$errors = [];
$name     = $user['name'];
$email    = $user['email'];
$imgName  = $user['profile_image'];
$passHash = $user['password'];

// 6) Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nameInput  = trim($_POST['name']  ?? '');
    $emailInput = trim($_POST['email'] ?? '');
    $newPass    = $_POST['password']  ?? '';

    // a) Validate name/email
    if ($nameInput === '' || $emailInput === '') {
        $errors[] = 'Name and email are required.';
    }

    // b) Validate email format
    if ($emailInput && !filter_var($emailInput, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    // c) Check for duplicate email
    if (empty($errors) && $emailInput !== $user['email']) {
        $check = $pdo->prepare("SELECT id FROM admins WHERE email = ? AND id <> ?");
        $check->execute([$emailInput, $id]);
        if ($check->fetch()) {
            $errors[] = 'That email is already in use.';
        }
    }

    // d) Handle password change
    if ($newPass !== '') {
        $passHash = password_hash($newPass, PASSWORD_DEFAULT);
    }

    // e) Handle avatar upload with validation
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg','jpeg','png','gif'];
        $ext     = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = 'Invalid avatar type. Only JPG, JPEG, PNG or GIF allowed.';
        } else {
            $newImg = uniqid('img_') . ".{$ext}";
            // remove old file if exists
            if ($imgName && file_exists(__DIR__ . "/uploads/$imgName")) {
                unlink(__DIR__ . "/uploads/$imgName");
            }
            move_uploaded_file(
                $_FILES['profile_image']['tmp_name'], 
                __DIR__ . "/uploads/$newImg"
            );
            $imgName = $newImg;
        }
    }

    // f) Update if no errors
    if (empty($errors)) {
        $upd = $pdo->prepare(
            "UPDATE admins SET name = ?, email = ?, password = ?, profile_image = ? WHERE id = ?"
        );
        $upd->execute([$nameInput, $emailInput, $passHash, $imgName, $id]);
        header('Location: users.php');
        exit;
    }

    // g) Repopulate form values on error
    $name  = $nameInput;
    $email = $emailInput;
}

// 7) Include header
require_once __DIR__ . '/includes/header.php';
?>

<main class="container">
  <h1 class="my-4">Edit Administrator</h1>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <ul>
        <?php foreach ($errors as $err): ?>
          <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="name" class="form-label">Name</label>
      <input type="text" id="name" name="name" class="form-control"
             value="<?= htmlspecialchars($name) ?>" required>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" id="email" name="email" class="form-control"
             value="<?= htmlspecialchars($email) ?>" required>
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">
        Password <small class="text-muted">(leave blank to keep current)</small>
      </label>
      <input type="password" id="password" name="password" class="form-control">
    </div>

    <?php if ($imgName): ?>
      <div class="mb-3">
        <p>Current Avatar:</p>
        <img src="/php-final-project/uploads/<?= htmlspecialchars($imgName) ?>"
             width="80" class="rounded-circle mb-2" alt="Avatar">
      </div>
    <?php endif; ?>

    <div class="mb-3">
      <label for="profile_image" class="form-label">Change Avatar</label>
      <input type="file" id="profile_image" name="profile_image"
             class="form-control" accept="image/*">
    </div>

    <button type="submit" class="btn btn-primary">Save Changes</button>
    <a href="users.php" class="btn btn-secondary">Cancel</a>
  </form>
</main>

<?php
// 8) Include footer
require_once __DIR__ . '/includes/footer.php';
?>
