<?php
// user_update.php
header('Content-Type: application/json; charset=utf-8');
include 'db_connect.php';

// Ensure POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

// Read and sanitize
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');

// Basic validation
$errors = [];

if ($id <= 0) $errors['id'] = "Invalid user id.";
if ($full_name === '') $errors['full_name'] = "Full name is required.";
if ($email === '') $errors['email'] = "Email is required.";
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Invalid email format.";
if ($phone === '') $errors['phone'] = "Phone is required.";
elseif (!preg_match('/^[0-9]{10}$/', $phone)) $errors['phone'] = "Phone must be 10 digits.";

// If validation errors, return them
if (!empty($errors)) {
    echo json_encode(["success" => false, "errors" => $errors]);
    exit;
}

// Prepare update
$stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, phone = ? WHERE id = ?");
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "DB prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("sssi", $full_name, $email, $phone, $id);

if (!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "DB execute failed: " . $stmt->error]);
    $stmt->close();
    exit;
}

// Check affected rows
if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "User updated successfully"]);
} else {
    // affected_rows == 0 could mean no changes or wrong id
    // check if user exists
    $check = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $check->store_result();
    if ($check->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "User not found (id " . $id . ")"]);
    } else {
        echo json_encode(["success" => false, "message" => "No changes made (values may be identical)"]);
    }
    $check->close();
}

$stmt->close();
$conn->close();
exit;
?>
