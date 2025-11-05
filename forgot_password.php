<?php
session_start();
require __DIR__.'/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = ''; // agar password hai to yahan daale
$DB_NAME = 'user_system';
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

    // check if user exists
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];
        $fullName = $user['username'];

        // generate token
        $token = bin2hex(random_bytes(16));
        $expires = date("Y-m-d H:i:s", strtotime("+30 minutes"));

        // save token in DB
        $ins = $conn->prepare("UPDATE users SET reset_token=?, reset_expires=? WHERE id=?");
        $ins->bind_param("ssi", $token, $expires, $userId);

        if ($ins->execute()) {
            // send email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = "smtp.gmail.com";
                $mail->SMTPAuth = true;
                $mail->Username = "jethvaaniruddhsinh007@gmail.com"; 
                $mail->Password = "xjbfjeggejdxhkqz";  
                $mail->SMTPSecure = "tls";
                $mail->Port = 587;

                $mail->setFrom("jethvaaniruddhsinh007@gmail.com", "My Website");
                $mail->addAddress($email, $fullName);

                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                $host = $_SERVER['HTTP_HOST'];
                $link = $protocol . '://' . $host . '/reset_password.php?token=' . $token;

                $mail->isHTML(true);
                $mail->Subject = "Reset Your Password";
                $mail->Body = "Hi $fullName,<br><br>
                    Click below to reset your password:<br>
                    <a href='$link'>Reset Password</a><br><br>
                    This link will expire in 30 minutes.";

                $mail->send();
                $message = "✅ Reset link sent! Check your email.";
            } catch (Exception $e) {
                $message = "❌ Token saved but email not sent. Error: " . $mail->ErrorInfo;
            }
        } else {
            $message = "❌ Something went wrong while saving token.";
        }

    } else {
        $message = "❌ No account found with this email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forgot Password</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
body {
    background: linear-gradient(135deg, #0a0a0a, #1e1e2f, #0a0a0a);
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.card {
    background: rgba(10,10,20,0.9);
    border-radius: 2rem;
    padding: 2.5rem;
    box-shadow: 0 0 30px rgba(139,92,246,0.6);
    backdrop-filter: blur(10px);
    width: 100%;
    max-width: 400px;
    animation: fadeIn 0.7s ease-in-out;
}

@keyframes fadeIn {
    from {opacity: 0; transform: translateY(-20px);}
    to {opacity: 1; transform: translateY(0);}
}

input:focus {
    box-shadow: 0 0 0 3px rgba(139,92,246,0.5);
    border-color: #8B5CF6;
}

button:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 20px rgba(139,92,246,0.5);
}
</style>
</head>
<body>

<div class="card">
    <h2 class="text-2xl md:text-3xl font-bold text-center text-purple-400 mb-6">Forgot Password</h2>

    <?php if ($message): ?>
        <p class="mb-4 text-center <?= strpos($message,'✅') !== false ? 'text-green-400' : 'text-red-500' ?> font-semibold">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="post" class="space-y-4">
        <div>
            <label class="block text-gray-300 font-semibold mb-1">Enter your email</label>
            <input type="email" name="email" required 
                class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-900 text-white focus:ring-2 focus:ring-purple-500 focus:outline-none" 
                placeholder="you@example.com">
        </div>
        <button type="submit" class="w-full py-3 rounded-lg bg-gradient-to-r from-purple-600 to-blue-500 text-white font-semibold shadow-md transition-transform">Send Reset Link</button>
    </form>
</div>

</body>
</html>
