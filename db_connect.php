<?php
require_once __DIR__ . '/config.php';

// Enable MySQLi exceptions for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Connect to MySQLi
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Set UTF-8 charset
$conn->set_charset("utf8mb4");
?>
