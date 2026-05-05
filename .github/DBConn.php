<?php
// Database connection configuration
$dbHost     = "localhost";
$dbUser     = "root";
$dbPass     = "";          // Change this if your MySQL has a password
$dbName     = "ClothingStore";

// Create MySQLi connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
