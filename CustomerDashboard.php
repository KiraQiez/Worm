<?php
$title = "Rent";
include 'CustomerHeader.php';

$sql = "SELECT book.bookTitle, book.bookAuthor, book.bookImage , rental.EndDate, rental.RentalStatus
        FROM book
        INNER JOIN rental ON book.bookID = rental.BookID
        WHERE rental.CustID = ? AND rental.RentalStatus = 'rent'
        ORDER BY  rental.EndDate ASC
        LIMIT 4";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userid);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="main-content d-flex">
    <div class="sidebar dashboard">
        <h4>Menu</h4>
        <hr>
        <ul>
            <li><a href="CustomerDashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="CustomerRental.php"><i class="fas fa-book"></i> Rental Books</a></li>
            <li><a href="CustomerHistory.php"><i class="fas fa-history"></i> My History</a></li>
            <li><a href="CustomerFine.php"><i class="fas fa-dollar-sign"></i> Pay Fine</a></li>
        </ul>
    </div>
    <div class="rent-content">
        <div class="event-banner mb-4">
            <div class="banner-content">
                <h1>Paperback Book Day</h1>
                <p style="color: #333">Paperback Book Day is celebrated on July 30th, marking the anniversary of the first Penguin paperback publication in 1935.
                    This day honors the affordability and accessibility of paperback books, encouraging readers to enjoy their favorite
                    titles in this portable format.</p>
                <a class="btn btn-primary" href="https://nationaltoday.com/paperback-book-day/">Find Out More</a>
            </div>
        </div>
        <div class="rent mb-4">
            <h2>On Rent</h2>
            <div class="rent-list img-fluid d-flex">
                <?php
                if ($result->num_rows > 0) {
                    $today = new DateTime();
                    while ($row = $result->fetch_assoc()) {
                        $dueDate = new DateTime($row['EndDate']);
                        $interval = $today->diff($dueDate);
                        $daysLeft = $interval->days;
                        $pastDue = ($interval->invert == 1);

                        $dueClass = '';
                        if ($pastDue) {
                            $dueClass = 'text-danger';
                        } else if ($daysLeft <= 10) {
                            $dueClass = 'text-warning';
                        }

                        echo '<div class="rent-card">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['bookImage']) . '" alt="' . $row['bookTitle'] . '">';
                        echo '<h3 class="title">' . $row['bookTitle'] . '</h3>';
                        echo '<p class="author">' . $row['bookAuthor'] . '</p>';

                        $formattedDate = $dueDate->format('d/m/Y');
                        echo '<p class="due ' . $dueClass . '"> Due: ' . $formattedDate . '</p>';
                        echo '</div>';
                    }
                }
                ?>

                <a href="CustomerCatalogue.php" class="add-rent-link">
                    <div class="add-rent">
                        <i class="fas fa-plus"></i>
                        <div class="cc">

                            <p>Borrow More Books</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="user-dashboard">
            <div class="cont">
                <h5>My History ⌚</h5>
                <?php
                $sqlHistory = "SELECT book.bookTitle, book.bookAuthor, book.bookImage , rental.EndDate, rental.RentalStatus
                FROM book
                INNER JOIN rental ON book.bookID = rental.BookID
                WHERE rental.CustID = ?
                ORDER BY rental.RentalID DESC
                LIMIT 4";
                $stmtHistory = $conn->prepare($sqlHistory);
                $stmtHistory->bind_param("s", $userid);
                $stmtHistory->execute();
                $resultHistory = $stmtHistory->get_result();
                if ($resultHistory->num_rows > 0) {
                    while ($row = $resultHistory->fetch_assoc()) {
                        echo '<div class="book-item">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['bookImage']) . '" alt="' . $row['bookTitle'] . '">';
                        echo '<div>';
                        echo '<h3 class="book-title">' . $row['bookTitle'] . '</h3>';
                        echo '<p class="book-author">' . $row['bookAuthor'] . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>You have no history yet</p>';
                }
                ?>

            </div>
            <div class="cont">
                <h5>Best Selling Books 🚀</h5>
                <?php
                // Best Selling Books
                $sqlBestSelling = "SELECT bookTitle, bookAuthor, bookImage FROM book LIMIT 4";
                $resultBestSelling = $conn->query($sqlBestSelling);
                if ($resultBestSelling->num_rows > 0) {
                    while ($row = $resultBestSelling->fetch_assoc()) {
                        echo '<div class="book-item">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['bookImage']) . '" alt="' . $row['bookTitle'] . '">';
                        echo '<div>';
                        echo '<h3 class="book-title">' . $row['bookTitle'] . '</h3>';
                        echo '<p class="book-author">' . $row['bookAuthor'] . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No best selling books available.</p>';
                } ?>

            </div>
            <div class="cont popular-reads">
                <h5>Random Picks ⭐ </h5>

                <?php
                $sqlRandomPicks = "SELECT bookTitle, bookAuthor, bookImage FROM book ORDER BY RAND() LIMIT 4";
                $resultRandomPicks = $conn->query($sqlRandomPicks);
                if ($resultRandomPicks->num_rows > 0) {
                    while ($row = $resultRandomPicks->fetch_assoc()) {
                        echo '<div class="book-item">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['bookImage']) . '" alt="' . $row['bookTitle'] . '">';
                        echo '<div>';
                        echo '<h3 class="book-title">' . $row['bookTitle'] . '</h3>';
                        echo '<p class="book-author">' . $row['bookAuthor'] . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No recommendations available.</p>';
                }
                ?>


            </div>
        </div>
    </div>
</div>

</body>

</html>
<?php
$conn->close();
?>