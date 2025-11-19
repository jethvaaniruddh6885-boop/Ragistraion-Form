<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include "db_connect.php";

$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

if ($role_id == 1) {
    $sql = "SELECT * FROM books";
} else {
    $sql = "SELECT b.*
            FROM books b
            JOIN book_assign a ON b.book_id = a.book_id
            WHERE a.user_id = $user_id";
}

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Books</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="script.js"></script>

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
}

.topbar {
    background: rgba(17,17,29,0.9);
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 15px rgba(139,92,246,0.2);
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
        <a href="user_dashboard.php" class="block px-3 py-2 rounded">🏠 Home</a>
        <a href="books.php" class="block px-3 py-2 rounded bg-purple-600">📚 Books</a>
    </nav>
</aside>

<!-- Main Section -->
<div class="flex-1 flex flex-col">

    <!-- Top Bar -->
    <header class="topbar flex items-center justify-between px-6 py-4">
        <h1 class="text-xl font-bold text-purple-400">Books</h1>

        <div class="relative">
            <button onclick="toggleDropdown()" 
                    class="flex items-center space-x-2 bg-gray-800 px-4 py-2 rounded-lg hover:bg-gray-700">
                <span><?php echo $_SESSION['username']; ?></span>
                ▼
            </button>
            <div id="dropdownMenu" class="hidden absolute right-0 mt-2 bg-gray-800 w-40 rounded-lg shadow-lg">
                <a href="logout.php" class="block px-4 py-2 hover:bg-gray-700">Logout</a>
            </div>
        </div>
    </header>

    <!-- Table Section -->
    <main class="p-6">

        <h1 class="text-3xl font-bold text-purple-400 mb-6">📚 Books List</h1>

        <table class="w-full bg-gray-800 rounded-lg overflow-hidden">
            <thead class="bg-gray-700">
                <tr>
                    <th class="p-3 text-left">ID</th>
                    <th class="p-3 text-left">Book Name</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>

            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr class="border-b border-gray-700">
                    <td class="p-3"><?= $row['book_id']; ?></td>
                    <td class="p-3"><?= $row['book_name']; ?></td>

                    <td class="p-3 flex space-x-3">

    <!-- View PDF in New Tab -->
    <a href="books/<?= $row['pdf_file']; ?>" 
       target="_blank"
       class="px-3 py-1 bg-blue-600 rounded hover:bg-blue-700">
       View
    </a>

    <!-- Download PDF -->
  
    <?php if ($role_id == 1): ?>
    <button onclick="assignBook(<?= $row['book_id']; ?>)" 
            class="px-3 py-1 bg-purple-600 rounded hover:bg-purple-700">
        Assign
    </button>
    <?php endif; ?>

</td>

                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

    </main>
</div>


<!-- Assign Modal -->
<div id="assignModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
  <div class="bg-gray-900 p-6 rounded-xl w-full max-w-xl border border-purple-700">
      <h2 class="text-xl font-bold text-purple-400 mb-4">Assign Book</h2>
      <div id="assignUserList" class="max-h-64 overflow-y-auto space-y-2 text-gray-300">
          Loading users...
      </div>

      <div class="flex justify-end mt-4 space-x-2">
        <button onclick="closeAssignModal()" class="bg-gray-700 px-4 py-2 rounded-lg">Cancel</button>
        <button onclick="submitAssign()" class="bg-green-600 px-4 py-2 rounded-lg">Submit</button>
      </div>
  </div>
</div>

</body>
</html>
