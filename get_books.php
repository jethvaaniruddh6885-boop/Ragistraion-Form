<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "user_system");

$sql = "SELECT book_id, book_name, author_name, pdf_file FROM books ORDER BY book_id DESC";
$result = $conn->query($sql);

$books = [];

while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

echo json_encode($books);
?>
