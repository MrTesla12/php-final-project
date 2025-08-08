<?php
// delete_user.php

// 1) Include the database connection
require_once __DIR__ . '/includes/db.php';

// 2) Start session if needed and protect the page
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// 3) Validate the incoming ID
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header('Location: users.php');
    exit;
}

// 4) Fetch the user record so we know the avatar filename
$stmt = $pdo->prepare("SELECT profile_image FROM admins WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // 5) If an avatar file exists, delete it
    if ($user['profile_image']) {
        $file = __DIR__ . "/uploads/{$user['profile_image']}";
        if (file_exists($file)) {
            unlink($file);
        }
    }

    // 6) Delete the user from the database
    $del = $pdo->prepare("DELETE FROM admins WHERE id = ?");
    $del->execute([$id]);
}

// 7) Redirect back to the manage-users page
header('Location: users.php');
exit;
