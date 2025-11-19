<?php
header('Content-Type: application/json');
include 'db_connect.php'; // must define $conn (mysqli) and connect to user_system

// validate POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit;
}
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$file = isset($_POST['file']) ? $_POST['file'] : '';

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid id']);
    exit;
}

// get file name from DB to be safe
$stmt = $conn->prepare("SELECT pdf_file FROM books WHERE book_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Record not found']);
    exit;
}
$row = $res->fetch_assoc();
$dbFile = $row['pdf_file'];

// Delete DB record
$stmt2 = $conn->prepare("DELETE FROM books WHERE book_id = ?");
$stmt2->bind_param("i", $id);
$ok = $stmt2->execute();

if (!$ok) {
    echo json_encode(['success' => false, 'message' => 'Failed to delete record']);
    exit;
}

// Delete physical file from disk
// Use server folder path (adjust if necessary)
$booksFolder = __DIR__ . '/books/'; // points to C:\laragon\www\User_RagistraionForm\books if this script is in that project folder
$targetFile = $booksFolder . $dbFile;

if ($dbFile && file_exists($targetFile)) {
    // safety: ensure file is inside the books folder
    $realBase = realpath($booksFolder);
    $realFile = realpath($targetFile);
    if ($realFile && strpos($realFile, $realBase) === 0) {
        @unlink($realFile);
    }
}

echo json_encode(['success' => true]);
exit;
?>
