<?php
// config.php

// DB connection settings
$host = "localhost";
$dbname = "case_portal";
$username = "root"; // Change if you have a different DB user
$password = "";     // Change if you have a DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Start session for login handling
session_start();
?>
