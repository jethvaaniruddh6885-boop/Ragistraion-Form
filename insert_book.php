<?php
header("Content-Type: application/json");

// DB connection
$conn = new mysqli("localhost", "root", "", "user_system");

$errors = [];
$book_name = trim($_POST['book_name'] ?? "");
$author_name = trim($_POST['author_name'] ?? "");

// Validation
if ($book_name == "") $errors['book_name'] = "Book Name is required.";
if ($author_name == "") $errors['author_name'] = "Author Name is required.";

if (!isset($_FILES['pdf_file']) || $_FILES['pdf_file']['error'] != 0) {
    $errors['pdf_file'] = "Please upload a PDF file.";
} else {
    $ext = strtolower(pathinfo($_FILES['pdf_file']['name'], PATHINFO_EXTENSION));
    if ($ext != "pdf") {
        $errors['pdf_file'] = "Only PDF files allowed.";
    }
}

// If validation errors
if (!empty($errors)) {
    echo json_encode(["success" => false, "errors" => $errors]);
    exit;
}

// SAVE PDF DIRECTLY IN:
// C:/laragon/www/User_RagistraionForm/books/
$upload_dir = "C:/laragon/www/User_RagistraionForm/books/";

if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

$file_name = time() . "_" . basename($_FILES['pdf_file']['name']);
$target = $upload_dir . $file_name;

// Upload PDF
move_uploaded_file($_FILES['pdf_file']['tmp_name'], $target);

// Insert Into DB
$stmt = $conn->prepare("INSERT INTO books (book_name, author_name, pdf_file) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $book_name, $author_name, $file_name);
$stmt->execute();

echo json_encode(["success" => true]);
?>
