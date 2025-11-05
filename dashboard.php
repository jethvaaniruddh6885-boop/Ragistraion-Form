<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
body {
    font-family: 'Inter', sans-serif;
    background: radial-gradient(circle at top, #0a0a0a, #1e1e2f);
    min-height: 100vh;
    color: #fff;
}

.sidebar {
    background: #11101d;
    box-shadow: 0 0 20px rgba(139,92,246,0.5);
}

.sidebar a:hover {
    background: #8B5CF6;
    color: #fff;
}

.topbar {
    background: rgba(17,17,29,0.9);
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 15px rgba(139,92,246,0.2);
}

.card {
    background: rgba(20,20,40,0.95);
    border-radius: 2rem;
    padding: 4rem 3rem;
    max-width: 600px;
    margin: auto;
    text-align: center;
    box-shadow: 0 0 50px rgba(139,92,246,0.7);
    animation: fadeIn 1s ease-in-out;
    transition: transform 0.3s, box-shadow 0.3s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0 60px rgba(139,92,246,0.9);
}

.card h1 {
    font-size: 2.5rem;
    font-weight: bold;
    background: linear-gradient(90deg, #8B5CF6, #3B82F6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 1rem;
}

.card p {
    color: #ccc;
    font-size: 1.2rem;
    margin-bottom: 2rem;
}

.card button {
    background: linear-gradient(to right, #8B5CF6, #3B82F6);
    padding: 0.75rem 2rem;
    border-radius: 1rem;
    font-weight: bold;
    color: white;
    transition: all 0.3s ease;
}

.card button:hover {
    transform: scale(1.05);
    box-shadow: 0 0 25px rgba(139,92,246,0.7);
}

@keyframes fadeIn {
    from {opacity: 0; transform: translateY(-20px);}
    to {opacity: 1; transform: translateY(0);}
}
</style>
<script>
function toggleDropdown() {
    document.getElementById('dropdownMenu').classList.toggle('hidden');
}
</script>
</head>
<body class="flex h-screen">

<!-- Sidebar -->
<aside class="sidebar w-64 flex flex-col text-white">
    <div class="p-5 text-2xl font-bold border-b border-gray-700">🌌 SpaceDash</div>
    <nav class="flex-1 p-4 space-y-2">
        <a href="#" class="block px-3 py-2 rounded transition-colors">🏠 Home</a>
        <a href="#" class="block px-3 py-2 rounded transition-colors">📦 Orders</a>
        <a href="#" class="block px-3 py-2 rounded transition-colors">👥 Users</a>
        <a href="#" class="block px-3 py-2 rounded transition-colors">⚙️ Settings</a>
    </nav>
</aside>

<!-- Main Content -->
<div class="flex-1 flex flex-col">

    <!-- Top Bar -->
    <header class="topbar flex items-center justify-between px-6 py-4">
        <h1 class="text-xl font-bold text-purple-400">Dashboard</h1>
        <div class="relative">
            <button onclick="toggleDropdown()" class="flex items-center space-x-2 bg-gray-800 px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                <span class="font-medium text-white"><?php echo htmlspecialchars($username); ?></span>
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <!-- Dropdown -->
            <div id="dropdownMenu" class="dropdown hidden absolute right-0 mt-2 w-40 bg-gray-800 border border-gray-700 rounded shadow-lg z-10">
                <a href="logout.php" class="block px-4 py-2 text-white rounded">Logout</a>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="flex-1 flex items-center justify-center p-6">
        <div class="card">
            <h1>Welcome, <?php echo htmlspecialchars($username); ?> 🌌</h1>
            <p>Glad to have you back! Explore your dashboard and manage your account easily.</p>
                   </div>
    </main>
</div>

</body>
</html>
