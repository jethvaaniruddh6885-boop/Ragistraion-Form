<?php
session_start();
include "db_connect.php";

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role_id'];

if ($role == 1) {
    // Admin = show all books
    $sql = "SELECT * FROM books";
} else {
    // User = show only assigned books
    $sql = "SELECT b.*
            FROM books b
            JOIN book_assign a ON b.book_id = a.book_id
            WHERE a.user_id = $user_id";
}

$res = $conn->query($sql);

$books = [];
while ($row = $res->fetch_assoc()) {
    $books[] = $row;
}

echo json_encode($books);
?>