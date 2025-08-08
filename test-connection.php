
<?php
// test-connection.php

// 1) Include the database connection
require_once __DIR__ . '/includes/db.php';



// 2) Perform a simple query to test
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM admins");
    $count = $stmt->fetchColumn();
    echo "✅ Connected successfully! There are {$count} admin records.";
} catch (Exception $e) {
    // If something goes wrong, show the error
    die("❌ Test query failed: " . $e->getMessage());
}
