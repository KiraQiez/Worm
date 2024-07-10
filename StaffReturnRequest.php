<?php
$title = "Return Request";
include 'StaffHeader.php';
include 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Pagination settings
$limit = 6; // Number of rentals per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
$offset = ($page - 1) * $limit; // Offset for SQL query

// Fetch total number of rentals with status 'Request'
$totalRentalsQuery = "SELECT COUNT(*) AS total FROM rental WHERE rentalStatus = 'Request'";
$totalRentalsResult = $conn->query($totalRentalsQuery);
$totalRentals = $totalRentalsResult->fetch_assoc()['total'];
$totalPages = ceil($totalRentals / $limit);

// Fetch rentals from the database with pagination
$rentals = [];
$sql = "SELECT r.*, b.bookTitle 
        FROM rental r
        INNER JOIN book b ON r.BookID = b.BookID
        WHERE r.rentalStatus = 'Request'
        ORDER BY r.endDate
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rentals[] = [
            'RentalID' => $row['RentalID'],
            'StartDate' => $row['StartDate'],
            'EndDate' => $row['EndDate'],
            'RentalStatus' => $row['RentalStatus'],
            'CustID' => $row['CustID'],
            'BookID' => $row['BookID'],
            'BookTitle' => $row['bookTitle'] // Include bookTitle from book table
        ];
    }
}

// Process update request if rentalID and status are set
if (isset($_GET['rentalID']) && isset($_GET['status'])) {
    $rentalID = $_GET['rentalID'];
    $status = $_GET['status'];
    $staffID = $_SESSION['userid']; // Correctly fetch the staff ID from the session

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update rental status and staff ID
        $stmt = $conn->prepare("UPDATE rental SET rentalStatus = ?, StaffID = ? WHERE RentalID = ?");
        $stmt->bind_param('ssi', $status, $staffID, $rentalID);
        $stmt->execute();

        // Update book status to 'Available' if the status is 'Returned'
        if ($status === 'Returned') {
            $bookIDQuery = $conn->prepare("SELECT BookID FROM rental WHERE RentalID = ?");
            $bookIDQuery->bind_param('i', $rentalID);
            $bookIDQuery->execute();
            $bookIDResult = $bookIDQuery->get_result();
            $bookIDRow = $bookIDResult->fetch_assoc();
            $bookID = $bookIDRow['BookID'];

            $updateBookStatusStmt = $conn->prepare("UPDATE book SET bookStatus = 'Available' WHERE BookID = ?");
            $updateBookStatusStmt->bind_param('i', $bookID);
            $updateBookStatusStmt->execute();
        }

        // Commit transaction
        $conn->commit();

        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Rental status updated successfully.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'StaffReturnRequest.php';
            });
        </script>";
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();

        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Failed to update rental status.',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'StaffReturnRequest.php';
            });
        </script>";
    }

    $stmt->close();
}
?>

<div class="main-content">
    <div class="contentBL">
        <div class="rental-list">
            <h4>Return Requests</h4>
            <?php if (empty($rentals)) : ?>
                <p>No return requests available.</p>
            <?php else : ?>
                <table>
                    <thead>
                        <tr>
                            <th class="id-col">Rental ID</th>
                            <th class="custid-col">Customer ID</th>
                            <th class="title-col">Book Title</th>
                            <th class="date-col">Start Date</th>
                            <th class="date-col">End Date</th>
                            <th class="status-col">Rental Status</th>
                            <th class="actions2-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rentals as $rental) : ?>
                            <tr>
                                <td class="id-col"><?php echo htmlspecialchars($rental['RentalID']); ?></td>
                                <td class="custid-col"><?php echo htmlspecialchars($rental['CustID']); ?></td>
                                <td class="title-col"><?php echo htmlspecialchars($rental['BookTitle']); ?></td>
                                <td class="date-col"><?php echo htmlspecialchars($rental['StartDate']); ?></td>
                                <td class="date-col"><?php echo htmlspecialchars($rental['EndDate']); ?></td>
                                <td class="status-col"><?php echo htmlspecialchars($rental['RentalStatus']); ?></td>
                                <td class="actions2-col">
                                    <button class="primary" onclick="updateRentalStatus('<?php echo htmlspecialchars($rental['RentalID']); ?>', 'Returned')">Accept</button>
                                    <button class="delete" onclick="updateRentalStatus('<?php echo htmlspecialchars($rental['RentalID']); ?>', 'Rent')">Reject</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <div class="pagination">
                <?php if ($page > 1) : ?>
                    <a href="?page=<?php echo $page - 1; ?>" class="prev">Previous</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
                <?php if ($page < $totalPages) : ?>
                    <a href="?page=<?php echo $page + 1; ?>" class="next">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    function updateRentalStatus(rentalID, status) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to " + (status === 'Returned' ? 'accept' : 'reject') + " this request.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, ' + (status === 'Returned' ? 'accept' : 'reject') + ' it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'StaffReturnRequest.php?rentalID=' + rentalID + '&status=' + status;
            }
        });
    }
</script>

</body>
</html>