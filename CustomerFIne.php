<?php
$title = "Rent";
include 'CustomerHeader.php';

?>

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
                include 'db.php';

                $sql = "SELECT book.bookTitle, rental.EndDate, rental.RentalStatus
                        FROM book
                        INNER JOIN rental ON book.bookID = rental.BookID
                        WHERE rental.CustID = ? AND rental.EndDate < CURDATE()";
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
                        echo '<td>RM ' . number_format($fine,2)  . '</td>';
                        $totalFine += $fine;
                    
                       
                        echo '</tr>';
                    }
                }
                ?>
            </tbody>
            </table>
            <hr>
            <div class="fine-total d-flex">
                <h3>Total Fine Amount: RM
                    <?php
                    echo number_format($totalFine,2);
                    ?>
                </h3>
                <?php
                // Check if user have returned all books
                $sqlCheck = "SELECT * FROM rental WHERE CustID = ? AND RentalStatus = 'Rent' AND EndData < CURDATE()";
                $stmtCheck = $conn->prepare($sqlCheck);
                $stmtCheck->bind_param("s", $userid);
                $stmtCheck->execute();
                $resultCheck = $stmtCheck->get_result();
                if ($resultCheck->num_rows == 0) {
                    $returned = false;
                    

                }
                else {
                    $returned = true;
                    }
                ?>
                <button class="btn btn-primary" onclick="checkReturn()">Pay Now</button>	
            </div>


        </div>
    </div>
</div>

<script>
function checkReturn(){
    var returned= <?php $returned ?>

    if(returned) {
        alert('Please return all book first before paying')
        } else {
            windows.location("CustomerFinePayment.php");
        }


        
    }

</script>

    </body>

    </html>