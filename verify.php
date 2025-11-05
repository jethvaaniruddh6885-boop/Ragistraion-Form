<?php
$conn = new mysqli("localhost", "root", "", "user_system");
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check user by token
    $stmt = $conn->prepare("SELECT * FROM users WHERE verify_token=? AND status='pending' LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update user status
        $update = $conn->prepare("UPDATE users SET status='active', verify_token=NULL WHERE verify_token=?");
        $update->bind_param("s", $token);
        if ($update->execute()) {
            // ✅ Redirect to dashboard after successful verification
            header("Location: dashboard.php?verified=1");
            exit();
        } else {
            echo "Something went wrong while updating.";
        }
    } else {
        echo "❌ Invalid or expired token!";
    }
} else {
    echo "❌ No token provided!";
}
?>
