<?php
$title = "Rented Book";
include 'StaffHeader.php';
include 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Pagination settings
$limit = 6; // Number of books per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
$offset = ($page - 1) * $limit; // Offset for SQL query

// Fetch total number of rented books
$totalRentedBooksQuery = "SELECT COUNT(*) AS total FROM book b 
                          INNER JOIN rental r ON b.bookID = r.BookID 
                          WHERE b.bookStatus = 'Rented'";
$totalRentedBooksResult = $conn->query($totalRentedBooksQuery);
$totalRentedBooks = $totalRentedBooksResult->fetch_assoc()['total'];
$totalPages = ceil($totalRentedBooks / $limit);

// Fetch rented books with pagination
$books = [];
$sql = "SELECT b.*, r.StartDate, r.EndDate, r.RentalStatus, r.CustID, r.RentalID
        FROM book b 
        INNER JOIN rental r ON b.bookID = r.BookID 
        WHERE b.bookStatus = 'Rented'
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = [
            'bookID' => $row['bookID'],
            'title' => $row['bookTitle'],
            'custID' => $row['CustID'],
            'rentStatus' => $row['RentalStatus'],
            'endDate' => $row['EndDate'],
            'rentID' => $row['RentalID']
        ];
    }
}
?>

<div class="main-content">
    <div class="content">
        <div class="book-list">
            <h4>Rented Books</h4>
            <?php if (empty($books)) : ?>
                <p>No books available.</p>
            <?php else : ?>
                <table>
                    <thead>
                        <tr>
                            <th class="rentID-col">Rent ID</th>
                            <th class="title-col">Book Title</th>
                            <th class="custID-col">Customer ID</th>
                            <th class="status-col">Status</th>
                            <th class="endDate-col">Due Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $book) : ?>
                            <tr>
                                <td class="rentID-col"><?php echo htmlspecialchars($book['rentID']); ?></td>
                                <td class="title-col"><?php echo htmlspecialchars($book['title']); ?></td>
                                <td class="custID-col"><?php echo htmlspecialchars($book['custID']); ?></td>
                                <td class="status-col"><?php echo htmlspecialchars($book['rentStatus']); ?></td>
                                <td class="endDate-col"><?php echo htmlspecialchars($book['endDate']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <!-- Pagination Controls -->
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

</body>

</html>