<?php
ob_start();
$title = "Delete Book";
include 'StaffHeader.php';
include 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$bookID = $_GET['bookID'];

if ($bookID) {
    $stmt = $conn->prepare("DELETE FROM book WHERE bookID = ?");
    $stmt->bind_param('s', $bookID);
    $stmt->execute();
}

header('Location: StaffBookList.php');
exit;
ob_start();
?>