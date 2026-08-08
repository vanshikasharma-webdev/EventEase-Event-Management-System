<?php

require_once __DIR__ . '/config.php';

// Database Credentials
$host = "localhost";
$username = "root";
$password = "";
$database = "eventease_db";

// Create Connection
$conn = new mysqli($host, $username, $password, $database);

// Connection Check
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Character Encoding
$conn->set_charset("utf8mb4");