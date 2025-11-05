<?php
session_start();
require 'db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // validate token
    $stmt = $conn->prepare("SELECT id, reset_expires FROM users WHERE reset_token=?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (strtotime($user['reset_expires']) < time()) {
            $error = "❌ Reset link expired.";
        }
    } else {
        $error = "❌ Invalid reset link.";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['token'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password=?, reset_token=NULL, reset_expires=NULL WHERE reset_token=?");
    $stmt->bind_param("ss", $password, $token);
    if ($stmt->execute()) {
        $success = "✅ Password updated successfully. <a href='login.php' class='text-purple-400 underline'>Login here</a>";
    } else {
        $error = "❌ Failed to update password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
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
    padding: 3rem;
    box-shadow: 0 0 40px rgba(139,92,246,0.6);
    backdrop-filter: blur(10px);
    width: 100%;
    max-width: 420px;
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

.icon {
    right: 1rem;
    top: 0.9rem;
    position: absolute;
    color: #aaa;
}
</style>
</head>
<body>

<div class="card">
    <h2 class="text-2xl md:text-3xl font-bold text-center text-purple-400 mb-6">Reset Your Password</h2>

    <?php if (!empty($error)): ?>
        <p class="text-red-500 mb-4 text-center font-medium"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <p class="text-green-400 mb-4 text-center font-medium"><?= $success ?></p>
    <?php else: ?>
        <?php if (isset($token) && empty($error)): ?>
            <form method="POST" class="space-y-5 relative">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <div class="relative">
                    <input type="password" name="password" placeholder="New Password" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-600 bg-gray-900 text-white focus:ring-2 focus:ring-purple-500 outline-none peer"/>
                    <i class="fas fa-lock icon"></i>
                </div>

                <div class="relative">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-600 bg-gray-900 text-white focus:ring-2 focus:ring-purple-500 outline-none peer"/>
                    <i class="fas fa-lock icon"></i>
                </div>

                <button type="submit"
                        class="w-full bg-gradient-to-r from-purple-600 to-blue-500 text-white py-3 rounded-xl font-semibold shadow-md transition-transform">
                    Update Password
                </button>
            </form>
        <?php endif; ?>
    <?php endif; ?>

    <p class="text-center text-gray-400 mt-6 text-sm">&copy; <?= date('Y') ?> My Website. All rights reserved.</p>
</div>

</body>
</html>
