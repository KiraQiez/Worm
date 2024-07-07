<?php
$title = "Book Details";
include 'StaffHeader.php';
include 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['userid'])) {
    $staffid = $_SESSION['userid'];
}

// Fetch book details
if (isset($_GET['bookID'])) {
    $bookID = $_GET['bookID'];
    $stmt = $conn->prepare("SELECT * FROM book WHERE bookID = ?");
    $stmt->bind_param('i', $bookID);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "No book selected.";
    exit;
}
?>

<div class="content">
    <div class="book-details d-flex justify-content-center">
        <div class="book-image">
            <img src="data:image/jpeg;base64,<?php echo base64_encode($book['bookImage']); ?>" alt="Book Image">
        </div>
        <div class="book-info">
            <h2><b><?php echo htmlspecialchars($book['bookTitle']); ?></b></h2>
            <p><strong>Author:</strong> <?php echo htmlspecialchars($book['bookAuthor']); ?></p>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($book['bookCategory']); ?></p>
            <p><strong>Price:</strong> $<?php echo htmlspecialchars($book['bookPrice']); ?></p>
            <p><strong>Date Published:</strong> <?php echo htmlspecialchars($book['bookDatePublished']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($book['bookStatus']); ?></p>
            <p><strong>Synopsis:</strong> <?php echo nl2br(htmlspecialchars($book['bookSynopsis'])); ?></p>
        </div>
    </div>
</div>
</body>

</html>