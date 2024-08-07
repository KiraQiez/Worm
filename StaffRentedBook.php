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

// Search query
$searchQuery = "";
$searchParam = "";
if (isset($_GET['search'])) {
    $searchParam = $_GET['search'];
    $searchQuery = "AND (b.bookTitle LIKE '%$searchParam%' OR r.CustID LIKE '%$searchParam%')";
}

// Fetch total number of rented books based on search query
$totalRentedBooksQuery = "SELECT COUNT(*) AS total 
                          FROM book b 
                          INNER JOIN rental r ON b.bookID = r.BookID 
                          WHERE b.bookStatus = 'Rented' AND r.RentalStatus <> 'Returned' $searchQuery";
$totalRentedBooksResult = $conn->query($totalRentedBooksQuery);
$totalRentedBooks = $totalRentedBooksResult->fetch_assoc()['total'];
$totalPages = ceil($totalRentedBooks / $limit);

// Fetch rented books with pagination and search query
$books = [];
$sql = "SELECT b.*, r.StartDate, r.EndDate, r.RentalStatus, r.CustID, r.RentalID
        FROM book b 
        INNER JOIN rental r ON b.bookID = r.BookID 
        WHERE b.bookStatus = 'Rented' AND r.RentalStatus <> 'Returned' $searchQuery
        ORDER BY r.EndDate 
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = [
            'bookID' => $row['bookID'],
            'title' => $row['bookTitle'],
            'custID' => $row['CustID'],
            'bookStatus' => $row['bookStatus'],
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
            <form method="get" action="">
                <input type="text" name="search" placeholder="Search by Book Title or Customer ID" class="special-text-input" value="<?php echo htmlspecialchars($searchParam); ?>">
                <button type="submit" class="secondary">Search</button>
            </form>
            <?php if (empty($books)) : ?>
                <p>No books available.</p>
            <?php else : ?>
                <table>
                    <thead>
                        <tr>
                            <th class="rentID-col">Rent ID</th>
                            <th class="title-col">Book Title</th>
                            <th class="custID-col">Customer ID</th>
                            <th class="status-col">Book Status</th>
                            <th class="status-col">Rental Status</th>
                            <th class="endDate-col">Due Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $book) : ?>
                            <tr>
                                <td class="rentID-col"><?php echo htmlspecialchars($book['rentID']); ?></td>
                                <td class="title-col"><?php echo htmlspecialchars($book['title']); ?></td>
                                <td class="custID-col"><?php echo htmlspecialchars($book['custID']); ?></td>
                                <td class="status-col"><?php echo htmlspecialchars($book['bookStatus']); ?></td>
                                <td class="status-col"><?php echo htmlspecialchars($book['rentStatus']); ?></td>
                                <td class="endDate-col" style="<?php echo (strtotime($book['endDate']) < time()) ? 'color: red;' : ''; ?>">
                                    <?php echo htmlspecialchars($book['endDate']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <!-- Pagination Controls -->
            <div class="pagination">
                <?php if ($page > 1) : ?>
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo htmlspecialchars($searchParam); ?>" class="prev">Previous</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <a href="?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($searchParam); ?>" class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
                <?php if ($page < $totalPages) : ?>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo htmlspecialchars($searchParam); ?>" class="next">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>