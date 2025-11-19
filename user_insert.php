<?php
// user_insert.php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
include 'db_connect.php';

$response = ["success" => false, "message" => "", "errors" => []];

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode($response);
    exit;
}

// read & sanitize
$full_name = trim($_POST['full_name'] ?? '');
$username  = trim($_POST['username'] ?? '');
$email     = trim($_POST['email'] ?? '');
$phone     = trim($_POST['phone'] ?? '');
$role_id   = trim($_POST['role_id'] ?? '');
$password  = trim($_POST['password'] ?? '');
$confirm   = trim($_POST['confirm_password'] ?? '');

// validation
if ($full_name === '') $response['errors']['full_name'] = "Full name is required.";
if ($username === '')  $response['errors']['username'] = "Username is required.";
if ($email === '')     $response['errors']['email'] = "Email is required.";
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $response['errors']['email'] = "Invalid email.";
if ($phone === '')     $response['errors']['phone'] = "Phone is required.";
if ($role_id === '')   $response['errors']['role_id'] = "Role is required.";
elseif (!preg_match('/^[0-9]{10}$/', $phone)) $response['errors']['phone'] = "Phone must be 10 digits.";
if ($password === '')  $response['errors']['password'] = "Password is required.";
if ($confirm === '')   $response['errors']['confirm_password'] = "Confirm password is required.";
elseif ($password !== $confirm) $response['errors']['confirm_password'] = "Passwords do not match.";

if (!empty($response['errors'])) {
    echo json_encode($response);
    exit;
}

// check duplicates
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
if ($stmt) {
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $response['message'] = "User exists with that email or username.";
        echo json_encode($response);
        $stmt->close();
        exit;
    }
    $stmt->close();
} else {
    $response['message'] = "DB error: " . $conn->error;
    echo json_encode($response);
    exit;
}

// insert
$hash = password_hash($password, PASSWORD_DEFAULT);
$status = "active"; // active by default


$stmt = $conn->prepare("INSERT INTO users (full_name, username, email, phone, password, role_id, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    $response['message'] = "DB prepare failed: " . $conn->error;
    echo json_encode($response);
    exit;
}

$stmt->bind_param("sssssis", $full_name, $username, $email, $phone, $hash, $role_id, $status);

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = "User created";
} else {
    $response['message'] = "Insert failed: " . $stmt->error;
}

$stmt->close();
$conn->close();
error_log("Insert Debug: " . print_r($response, true));


echo json_encode($response);
exit;
