<?php
// delete_content.php

// 1) Include PDO connection
require_once __DIR__ . '/../includes/db.php';

// 2) Start session & protect page
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// 3) Validate ID
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header('Location: content.php');
    exit;
}

// 4) Delete the content
$del = $pdo->prepare("DELETE FROM content WHERE id = ?");
$del->execute([$id]);

// 5) Redirect back
header('Location: content.php');
exit;
