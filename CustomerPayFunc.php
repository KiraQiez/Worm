<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $token = $input['token'];

    if ($token === $_SESSION['payment_token']) {
        // Assuming $userid is already set with the user's ID from the session or another method
        $userid = $_SESSION['userid'];

        $sqlUpdate = "UPDATE fine
                      INNER JOIN rental ON fine.RentalID = rental.RentalID
                      SET FineStatus = 'Paid'
                      WHERE rental.CustID = ? AND fine.FineStatus = 'Unpaid'";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("s", $userid);

        if ($stmtUpdate->execute()) {
            echo 'success';
            // Destroy the token after successful update
            unset($_SESSION['payment_token']);
        } else {
            echo 'error';
        }

        //Update Customer Status
        $sqlUpdate = "UPDATE customer SET Status = 'active' WHERE CustID = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("s", $userid);
        $stmtUpdate->execute();

        

        $stmtUpdate->close();
        $conn->close();
    } else {
        echo 'invalid token';
    }
}else{
    echo 'invalid request';
    header('Location: MainHomepage.php');
}
?>
