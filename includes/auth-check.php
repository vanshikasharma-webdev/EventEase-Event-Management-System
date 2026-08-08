<?php

// Start session if it has not already been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Prevent Browser Caching
|--------------------------------------------------------------------------
*/

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

/*
|--------------------------------------------------------------------------
| User Authentication
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['user_id'])) {
    header("Location: /EventEase/auth/login.php");
    exit();
}