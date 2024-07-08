<?php
$title = "Rent";
include 'CustomerHeader.php';

// Check if the user has returned all books
include 'db.php';
$sqlCheck = "SELECT * FROM rental WHERE CustID = ? AND RentalStatus = 'Rent' AND EndDate < CURDATE()";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("s", $userid);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();
$returned = ($resultCheck->num_rows == 0);

$showAlert = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['checkReturn'])) {
        if (!$returned) {
            $showAlert = true;
        }else {
            
            $token = bin2hex(random_bytes(32));
            $_SESSION['payment_token'] = $token;
        }
    }
}
?>

<?php if ($showAlert) : ?>
    <div class="alert alert-warning" role="alert">Please return all books first before paying the fine</div>
<?php endif; ?>

<div class="main-content d-flex">
    <div class="sidebar dashboard">
        <h4>Menu</h4>
        <hr>
        <ul>
            <li><a href="CustomerDashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="CustomerRental.php"><i class="fas fa-book"></i> Rental Books</a></li>
            <li><a href="CustomerHistory.php"><i class="fas fa-history"></i> My History</a></li>
            <li><a href="CustomerFine.php" class="active"><i class="fas fa-dollar-sign"></i> Pay Fine</a></li>
        </ul>
    </div>
    <div class="rent-content fine-content">
        <div class="rent mb-4">
            <h2>Pay Fine</h2>
            <hr>
            <div class="fine">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Bil</th>
                            <th>Book Title</th>
                            <th>End Date</th>
                            <th>Return Date</th>
                            <th>Return Status</th>
                            <th>Past Due</th>
                            <th>Fine Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Assuming $userid is already set with the user's ID
                        $sql = "SELECT book.bookTitle, rental.EndDate, rental.RentalStatus, rental.RentalID
                                FROM book
                                INNER JOIN rental ON book.bookID = rental.BookID
                                LEFT JOIN fine ON rental.RentalID = fine.RentalID
                                WHERE rental.CustID = ? AND rental.EndDate < CURDATE() AND (fine.FineStatus IS NULL OR fine.FineStatus <> 'Paid')";
                        $stmt = $conn->prepare($sql);
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $userid);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $bil = 1;
                        $totalFine = 0;
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $endDate = new DateTime($row['EndDate']);
                                $currentDate = new DateTime();
                                $interval = $endDate->diff($currentDate);
                                $exceedDate = $interval->days;
                                $fine = $exceedDate * 0.50;

                                echo '<tr>';
                                echo '<td>' . $bil++ . '</td>';
                                echo '<td>' . $row['bookTitle'] . '</td>';
                                echo '<td>' . $row['EndDate'] . '</td>';
                                echo '<td>' . $currentDate->format('Y-m-d') . '</td>';
                                echo '<td>' . $row['RentalStatus'] . '</td>';
                                echo '<td>' . $exceedDate . ' days</td>';
                                echo '<td>RM ' . number_format($fine, 2) . '</td>';
                                $totalFine += $fine;
                                echo '</tr>';

                                $sqlCheck = "SELECT * FROM fine WHERE RentalID = ?";
                                $stmtCheck = $conn->prepare($sqlCheck);
                                $stmtCheck->bind_param("s", $row['RentalID']);
                                $stmtCheck->execute();
                                $resultCheck = $stmtCheck->get_result();

                                if ($resultCheck->num_rows == 0) {
                                    $sqlInsert = "INSERT INTO fine (FineAmount, FineStatus, RentalID) VALUES (?, 'Unpaid', ?)";
                                    $stmtInsert = $conn->prepare($sqlInsert);
                                    $stmtInsert->bind_param("ds", $fine, $row['RentalID']);
                                    $stmtInsert->execute();
                                } else {
                                    $sqlUpdate = "UPDATE fine SET FineAmount = ? WHERE   RentalID = ?";
                                    $stmtUpdate = $conn->prepare($sqlUpdate);
                                    $stmtUpdate->bind_param("ds", $fine, $row['RentalID']);
                                    $stmtUpdate->execute();
                                }
                            }
                        }else{
                            echo '<tr><td colspan="7" class="text-center">No fine for you</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
                <hr>
                <div class="fine-total d-flex">
                    <h3>Total Fine Amount: RM
                        <?php
                        echo number_format($totalFine, 2);
                        ?>
                    </h3>
                    <div style="margin-left:auto;">
                        <form method="POST">
                            <input type="hidden" name="checkReturn" value="1">
                            <button class="btn btn-primary" type="submit">Pay Now</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($returned && $_SERVER['REQUEST_METHOD'] == 'POST') : ?>
    <div class="modal fade show" id="PayFineModal" tabindex="-1" aria-labelledby="suspendModalLabel" aria-hidden="true" style="display: block; background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body d-flex justify-content-center align-items-center">
                    <div class="card p-4">
                        <h1 class="title text-center mb-4">Paying Fine</h1>
                        <img src="rsc/image/PaymentQR.png" alt="QR Code for Payment" class="img-fluid mx-auto d-block">
                        <div class="alert alert-danger text-center mt-4" role="alert">
                            <strong>Important:</strong> The total fine amount is <strong>RM <?php echo number_format($totalFine, 2); ?></strong>. Please scan the QR code to pay the fine.
                        </div>
                        <a href="CustomerFine.php?cancel=1" class="btn btn-primary btn-block mb-3">Close</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    async function checkPayment() {
        const response = await fetch('payment_status.txt');
        const text = await response.text();
        if (text.trim() === 'confirmed') {
            Swal.fire({
                icon: 'success',
                title: 'Payment successful!',
                text: 'You have successfully paid the fine.',
                showConfirmButton: false,
                timer: 2000
            }).then(async () => {
                await fetch('CustomerPayFunc.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ token: '<?php echo $token; ?>' })
                });
                await fetch('delete_payment_status.php', {
                    method: 'POST'
                });

                location.href = 'CustomerFine.php';
            });

            // Refresh page
            setTimeout(() => {
                window.location.reload();
            }, 3000);
        } else {
            setTimeout(checkPayment, 1000);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        checkPayment();
    });
</script>
<?php
// Invalidate the token if the user cancels the payment
if (isset($_GET['cancel']) && $_GET['cancel'] == 1) {
    unset($_SESSION['payment_token']);
}
?>


</body>
</html>
