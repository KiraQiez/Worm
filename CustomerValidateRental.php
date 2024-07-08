<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bookID = intval($_POST['bookID']);
    $startRent = $_POST['startRent'];
    $custID = $_SESSION['userid']; 

    if ($startRent < date("Y-m-d")) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid date.']);
        exit;
    }

    // Check for existing rentals
    $stmt = $conn->prepare("SELECT * FROM rental WHERE BookID = ? AND RentalStatus = 'Rent'");
    $stmt->bind_param('i', $bookID);
    $stmt->execute();
    $result = $stmt->get_result();
    $canRent = true;
    $conflictingDates = [];

    $endRent = date('Y-m-d', strtotime($startRent . ' + 60 days'));
    $requestedStartRent = new DateTime($startRent);
    $requestedEndRent = new DateTime($endRent);

    while ($rental = $result->fetch_assoc()) {
        $existingStartRent = new DateTime($rental['StartDate']);
        $existingEndRent = new DateTime($rental['EndDate']);

        // Check for rental conflicts
        if ($requestedStartRent <= $existingEndRent && $requestedEndRent >= $existingStartRent) {
            $canRent = false;
            $conflictingDates[] = $existingStartRent->format('Y-m-d') . ' to ' . $existingEndRent->format('Y-m-d');
        }
    }
    $stmt->close();

    if (!$canRent) {
        echo json_encode(['status' => 'error', 'message' => 'Book is currently rented out on the following dates: ' . implode(', ', $conflictingDates)]);
        exit;
    }

    echo json_encode(['status' => 'success']);
    exit;
}
?>
