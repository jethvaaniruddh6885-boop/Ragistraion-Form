<?php
include 'db_connect.php'; // your DB connection file

$id = $_GET['id'] ?? 0;
if (!$id) {
  echo json_encode(null);
  exit;
}

$stmt = $conn->prepare("SELECT id, full_name, email, phone, role_id FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  echo json_encode($result->fetch_assoc());
} else {
  echo json_encode(null);
}

$stmt->close();
$conn->close();
?>
