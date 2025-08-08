<?php
// logout.php

// 1) Start session 
session_start();
// 2) Unset all session variables
$_SESSION = [];
// 3) Destroy the session
session_destroy();
// 4) Redirect back to home
header('Location: index.php');
exit;
