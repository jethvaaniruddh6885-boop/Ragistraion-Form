<?php
header("Content-Type: application/json");
include 'db_connect.php';

$result = $conn->query("SELECT id, full_name, phone, email, role_id FROM users ");
$users = [];
while ($row = $result->fetch_assoc()) $users[] = $row;

echo json_encode($users);
$conn->close();
?>
