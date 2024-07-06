<?php
$title = "Rent";
include 'CustomerHeader.php';

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check user ID from session
if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
} else {
    die("User ID not found in session.");
}

// Prepare and execute the SQL query to fetch rented books
$sql = "SELECT book.bookID, book.bookTitle, book.bookAuthor, book.bookImage, rental.EndDate, rental.RentalStatus, book.bookSynopsis
        FROM book
        INNER JOIN rental ON book.bookID = rental.BookID
        WHERE rental.CustID = ? AND rental.RentalStatus = 'out'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    echo "Error fetching data: " . $conn->error;
}
?>

<?php
// Handle book return
if (isset($_POST['return'])) {
    $bookID = $_POST['bookID'];
    
    // Update rental status
    $sql = "UPDATE rental SET RentalStatus = 'returned' WHERE BookID = ? AND CustID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $bookID, $userid);
    $stmt->execute();

    if ($stmt->error) {
        echo '<div class="alert alert-danger" role="alert">Error returning book: ' . $stmt->error . '</div>';
    } else {
        // Update book availability
        $sql2 = "UPDATE book SET bookStatus = 'available' WHERE bookID = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $bookID);
        $stmt2->execute();

        if ($stmt2->error) {
            echo '<div class="alert alert-danger" role="alert">Error updating book availability: ' . $stmt2->error . '</div>';
        } else {
            if ($stmt->affected_rows > 0 && $stmt2->affected_rows > 0) {
                echo '<div class="alert alert-success" role="alert">Book returned successfully.</div>';
            } else {
                echo '<div class="alert alert-warning" role="alert">No changes made (perhaps the book was already returned).</div>';
            }
        }
    }

    echo '<meta http-equiv="refresh" content="2">';

}
?>



<div class="main-content d-flex">
    <div class="sidebar dashboard">
        <h4>Menu</h4>
        <hr>
        <ul>
            <li><a href="CustomerDashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="CustomerRental.php" class="active"><i class="fas fa-book"></i> Rental Books</a></li>
            <li><a href="CustomerHistory.php"><i class="fas fa-history"></i> My History</a></li>
        </ul>
    </div>
    <div class="rent-content">
        <div class="rent mb-4">
            <h2>On Rent</h2>
            <hr>
            <div class="rent-list">
                <?php

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $date = $row['EndDate'];
                        $formattedDate = date('d/m/Y', strtotime($date));
                        echo '<div class="rent-card" data-book-id="' . $row['bookID'] . '" data-book-title="' . $row['bookTitle'] . '" data-book-author="' . $row['bookAuthor'] . '" data-book-synopsis="' . htmlspecialchars($row['bookSynopsis'], ENT_QUOTES) . '" data-book-due="' . $formattedDate . '">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['bookImage']) . '" alt="' . $row['bookTitle'] . '">';
                        echo '<h3 class="title">' . $row['bookTitle'] . '</h3>';
                        echo '<p class="author">' . $row['bookAuthor'] . '</p>';
                        echo '<p class="due"> Due: ' . $formattedDate . '</p>';
                        echo '<button type="button" class="btn btn-primary detail" data-bs-toggle="modal" data-bs-target="#bookDetailsModal">Open Details</button>';
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
    </div>
</div>


<div class="modal fade" id="bookDetailsModal" tabindex="-1" aria-labelledby="bookDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookDetailsModalLabel">Book Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <input type="hidden" name="bookID" id="modalBookID" value="">
            <div class="modal-body d-flex">
                <div class="book-image-modal pe-3">
                    <img id="modalBookImage" src="" alt="Book Image" class="img-fluid" style="max-width: 200px; height: auto;">
                </div>
                <div class="modal-c">
                    <div class="book-info-modal">
                        <h1 id="modalBookTitle" class="fs-5 fw-bold"></h1>
                        <h4 id="modalBookAuthor" class="mb-2"></h4>
                        <p id="modalBookSynopsis" class="text-muted"></p>

                    </div>
                    <div class="modal-due-date">
                        <p class="text-muted due-modal">Due: <span id="modalBookDue"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="return" class="btn btn-primary">Return Book</button>
            </div>
            </form>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
   $('.detail').click(function(event) {
    var card = $(this).closest('.rent-card');
    var bookId = card.data('book-id');
    console.log("Book ID: ", bookId);

    $('#modalBookImage').attr('src', card.find('img').attr('src'));
    $('#modalBookTitle').text(card.data('book-title'));
    $('#modalBookAuthor').text(card.data('book-author'));
    $('#modalBookSynopsis').text(card.data('book-synopsis'));
    $('#modalBookDue').text(card.data('book-due'));
    $('#modalBookID').val(bookId); 

    $('#bookDetailsModal').modal('show');
});




</script>


</body>

</html>
<?php
$conn->close();
?>