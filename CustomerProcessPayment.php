<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize input
    $bookID = intval($_POST['bookID']);
    $startRent = $_POST['startRent'];
    
    // Retrieve custID from session
    $custID = $_SESSION['userid']; // Assuming 'userid' is the session variable storing custID
    $receipt = file_get_contents($_FILES['receipt']['tmp_name']);
    
    // Calculate rental prices and duration
    $rentalPrice = 5.00;
    $rentalDeposit = getBookPrice($bookID);
    $subtotal = $rentalPrice + $rentalDeposit;
    $endRent = date('Y-m-d', strtotime($startRent . ' + 60 days'));
    $rentalStatus = "out"; // Setting rental status to 'out'

    // Insert into rental table
    $stmt = $conn->prepare("INSERT INTO rental (StartDate, EndDate, RentalStatus, RentalPrice, RentalDeposit, RentalDuration, CustID, BookID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        error_log('mysqli statement prepare error:' . $conn->error);
        die('An error occurred while processing your request.');
    }
    
    $rentalDuration = 60;
    $stmt->bind_param('sssddisi', $startRent, $endRent, $rentalStatus, $rentalPrice, $rentalDeposit, $rentalDuration, $custID, $bookID);
    if ($stmt->execute() === false) {
        error_log('mysqli statement execute error:' . $stmt->error);
        die('An error occurred while processing your request.');
    }
    $rentalID = $stmt->insert_id;
    $stmt->close();

    // Update book status to 'rented'
    $stmt = $conn->prepare("UPDATE book SET bookStatus = 'Rented' WHERE bookID = ?");
    if ($stmt === false) {
        error_log('mysqli statement prepare error:' . $conn->error);
        die('An error occurred while updating book status.');
    }
    $stmt->bind_param('i', $bookID);
    if ($stmt->execute() === false) {
        error_log('mysqli statement execute error:' . $stmt->error);
        die('An error occurred while updating book status.');
    }
    $stmt->close();

    // Insert into payment table
    $payDate = date('Y-m-d');
    $stmt = $conn->prepare("INSERT INTO payment (PayAmount, PayDate, PayReceipt, RentalID) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        error_log('mysqli statement prepare error:' . $conn->error);
        die('An error occurred while processing your request.');
    }
    $stmt->bind_param('dssi', $subtotal, $payDate, $receipt, $rentalID);
    if ($stmt->execute() === false) {
        error_log('mysqli statement execute error:' . $stmt->error);
        die('An error occurred while processing your request.');
    }
    $stmt->close();
}

function getBookPrice($bookID) {
    global $conn;
    $stmt = $conn->prepare("SELECT bookPrice FROM book WHERE bookID = ?");
    if ($stmt === false) {
        error_log('mysqli statement prepare error:' . $conn->error);
        die('An error occurred while fetching book price.');
    }
    $stmt->bind_param('i', $bookID);
    if ($stmt->execute() === false) {
        error_log('mysqli statement execute error:' . $stmt->error);
        die('An error occurred while fetching book price.');
    }
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
    $stmt->close();
    return $book['bookPrice'];
}
?>