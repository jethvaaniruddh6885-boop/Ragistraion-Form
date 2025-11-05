<?php
// Update these with your DB credentials
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'user_system';


$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
die('Database connection failed: ' . $conn->connect_error);
}
?>