<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    session_start();
    $userid = $_SESSION['userid'];

    $sqlUpdate = "UPDATE fine
                  INNER JOIN rental ON fine.RentalID = rental.RentalID
                  SET FineStatus = 'Paid'
                  WHERE rental.CustID = ? AND fine.FineStatus = 'Unpaid'";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("s", $userid);

    if ($stmtUpdate->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmtUpdate->close();
    $conn->close();
}
?>
