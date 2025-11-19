<?php
session_start();
include "db_connect.php";

$data = json_decode(file_get_contents("php://input"), true);

$book_id = $data['book_id'];
$users = $data['users'];

if (!$book_id || empty($users)) {
    echo "Invalid data";
    exit;
}

foreach ($users as $u) {

    // Already assigned check
    $check = $conn->query("SELECT id FROM book_assign WHERE book_id = $book_id AND user_id = $u");

    if ($check->num_rows == 0) {
        $conn->query("INSERT INTO book_assign (book_id, user_id) VALUES ($book_id, $u)");
    }
}

echo "Book Assigned Successfully!";
?>
