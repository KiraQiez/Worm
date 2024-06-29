<?php
$title = "View Book";
include 'StaffHeader.php';
include 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure bookID is provided and valid
if (!isset($_GET['bookID']) || !is_numeric($_GET['bookID'])) {
    // Handle case where bookID is missing or invalid
    header("Location: StaffBookList.php"); // Redirect to book list or error page
    exit();
}

$bookID = $_GET['bookID'];

// Fetch book details from the database
$sql = "SELECT * FROM book WHERE bookID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bookID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $book = $result->fetch_assoc();
} else {
    // Handle case where bookID does not exist in database
    header("Location: StaffBookList.php"); // Redirect to book list or error page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="catalogue.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body>
    <div class="main-content">
        <div class="book-details">
            <h2><?php echo htmlspecialchars($book['bookTitle']); ?></h2>
            <div class="book-info">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($book['bookImage']); ?>" alt="Book Image">
                <p><strong>Author:</strong> <?php echo htmlspecialchars($book['bookAuthor']); ?></p>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($book['bookCategory']); ?></p>
                <p><strong>Price:</strong> <?php echo htmlspecialchars($book['bookPrice']); ?></p>
                <p><strong>Synopsis:</strong><br><?php echo htmlspecialchars($book['bookSynopsis']); ?></p>
                <p><strong>Date Published:</strong> <?php echo htmlspecialchars($book['bookDatePublished']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($book['bookStatus']); ?></p>
            </div>
            <div class="button-group">
                <button onclick="window.location.href='StaffEditBook.php?bookID=<?php echo $bookID; ?>'">Edit</button>
                <button onclick="deleteBook(<?php echo $bookID; ?>)">Delete</button>
            </div>
        </div>
    </div>

    <script>
    function deleteBook(bookID) {
        if (confirm('Are you sure you want to delete this book?')) {
            window.location.href = 'StaffDeleteBook.php?bookID=' + bookID;
        }
    }
    </script>
</body>
</html>