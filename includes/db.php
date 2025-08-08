<?php
// includes/db.php

// 1. Database credentials
$host   = '127.0.0.1';            
$dbname = 'php_final_project';    
$user   = 'root';                 
$pass   = '';                     

// 2. Data Source Name (DSN) – tells PDO how to connect
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

try {
    // 3. Create PDO instance
    $pdo = new PDO($dsn, $user, $pass);
    
    // 4. Set error mode to Exception for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    
} catch (PDOException $e) { // 5. Handle connection errors
    // If connection fails, output the error and stop execution
    die("Database connection failed: " . $e->getMessage());
}
