<?php
header('Content-Type: application/json');
require __DIR__.'/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// DB connect
$conn = new mysqli("localhost", "root", "", "user_system");
if($conn->connect_error){
    echo json_encode(['general' => 'DB connection failed']);
    exit;
}

$response = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $fullName = trim($_POST['fullName'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    // Validation
    if($fullName==="") $response['fullName'] = "Full Name is required";
    if($username==="") $response['username'] = "Username is required";
    if($email==="") $response['email'] = "Email is required";
    if($phone==="") $response['phone'] = "Phone is required";
    if($password==="") $response['password'] = "Password is required";
    if($confirmPassword==="") $response['confirmPassword'] = "Confirm your password";
    if($password !== $confirmPassword) $response['confirmPassword'] = "Passwords do not match";


    $stmt = $conn->prepare("SELECT id FROM users WHERE username=? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows > 0) $response['username'] = "Username already taken";
    $stmt->close();
    // Duplicate email check
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows > 0) $response['email'] = "Email already registered";
    $stmt->close();

    if(count($response) > 0){
        echo json_encode($response);
        exit;
    }

    // Insert
    $hashed = password_hash($password, PASSWORD_BCRYPT);
    $token  = bin2hex(random_bytes(32));
    $status = "pending";

    $ins = $conn->prepare("INSERT INTO users (full_name, username, email, phone, password, status, verify_token) VALUES (?,?,?,?,?,?,?)");
    $ins->bind_param("sssssss", $fullName, $username, $email, $phone, $hashed, $status, $token);

    if($ins->execute()){
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "jethvaaniruddhsinh007@gmail.com"; // replace
            $mail->Password = "xjbfjeggejdxhkqz";   // replace
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;

            $mail->setFrom("jethvaaniruddhsinh007@gmail.com", "My Website");
            $mail->addAddress($email, $fullName);

            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $link = $protocol.'://'.$host.'/User_RagistraionForm/verify.php?token='.$token;

            $mail->isHTML(true);
            $mail->Subject = "Verify your email";
            $mail->Body = "Hi $fullName,<br><br>
            Click below to verify your account:<br>
            <a href='http://localhost:8081/User_RagistraionForm/verify.php?token=$token'>Verify Email</a>";

            $mail->send();
            echo json_encode(['success' => 'Registration successful! Check your email to verify.']);
        } catch (Exception $e){
            echo json_encode(['general' => 'Registered but email not sent.']);
        }
    } else {
        echo json_encode(['general' => 'Something went wrong. Try again!']);
    }
}
