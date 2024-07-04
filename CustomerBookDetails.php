<?php
$title = "Book Details";
include 'CustomerHeader.php';
include 'db.php';

if (isset($_GET['bookID'])) {
    $bookID = $_GET['bookID'];
    $stmt = $conn->prepare("SELECT * FROM book WHERE bookID = ?");
    $stmt->bind_param('i', $bookID);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
} else {
    echo "No book selected.";
    exit;
}
?>

<div class="main-content">
    <div class="book-details">
        <img src="data:image/jpeg;base64,<?php echo base64_encode($book['bookImage']); ?>" alt="Book Image">
        <h2><?php echo $book['bookTitle']; ?></h2>
        <p><strong>Author:</strong> <?php echo $book['bookAuthor']; ?></p>
        <p><strong>Category:</strong> <?php echo $book['bookCategory']; ?></p>
        <p><strong>Price:</strong> $<?php echo $book['bookPrice']; ?></p>
        <p><strong>Date Published:</strong> <?php echo $book['bookDatePublished']; ?></p>
        <p><strong>Status:</strong> <?php echo $book['bookStatus']; ?></p>
        <p><strong>Synopsis:</strong> <?php echo nl2br($book['bookSynopsis']); ?></p>
        <button class="btn btn-success">Checkout</button>
    </div>
</div>

</body>
</html>
