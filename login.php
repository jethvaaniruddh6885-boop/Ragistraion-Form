<?php
session_start();
require 'db.php';
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier'] ?? ''); // username or email
    $password = $_POST['password'] ?? '';

    if ($identifier === '' || $password === '') {
        $error = 'Please enter username/email and password.';
    } else {
        // Prepared statement to avoid SQL injection
        $stmt = $conn->prepare('SELECT id, username, email, password, status FROM users WHERE username = ? OR email = ? LIMIT 1');
        $stmt->bind_param('ss', $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                if ($user['status'] === 'pending') {
                    $error = '⚠️ Your account is still pending approval or verification.';
                } elseif ($user['status'] === 'active') {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    header('Location: dashboard.php');
                    exit;
                } else {
                    $error = '❌ Unknown account status.';
                }
            } else {
                $error = 'Invalid credentials.';
            }
        } else {
            $error = 'Invalid credentials.';
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
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
    <h2 class="text-2xl md:text-3xl font-bold text-center text-purple-400 mb-6">Login</h2>

    <?php if ($error): ?>
    <div class="mb-4 text-red-500 font-semibold"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" action="" class="space-y-4">
        <div>
            <label class="block text-gray-300 font-semibold mb-1">Username or Email</label>
            <input name="identifier" type="text" value="<?php echo isset($identifier) ? htmlspecialchars($identifier) : ''; ?>" 
                class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-900 text-white focus:ring-2 focus:ring-purple-500 focus:outline-none" 
                placeholder="username or email">
        </div>

        <div>
            <label class="block text-gray-300 font-semibold mb-1">Password</label>
            <input name="password" type="password" 
                class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-900 text-white focus:ring-2 focus:ring-purple-500 focus:outline-none" 
                placeholder="password">
        </div>

        <button type="submit" class="w-full py-3 rounded-lg bg-gradient-to-r from-purple-600 to-blue-500 text-white font-semibold shadow-md transition-transform">Login</button>
    </form>

    <p class="mt-4 text-sm text-gray-300 text-center">Don't have an account? <a href="register.php" class="text-purple-400">Register</a></p>
    <p class="mt-2 text-sm text-center"><a href="forgot_password.php" class="text-purple-400">Forgot Password?</a></p>
</div>

</body>
</html>
