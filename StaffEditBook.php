<?php
$title = "Edit Book";
include 'StaffHeader.php';
include 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the bookID is set in the URL
if (!isset($_GET['bookID'])) {
    echo "No book ID provided!";
    exit;
}

$bookID = $_GET['bookID'];

// Check if the request method is POST to handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookID = $_POST['bookID'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $synopsis = $_POST['synopsis'];
    $datePublished = $_POST['datePublished'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE book SET bookTitle = ?, bookAuthor = ?, bookCategory = ?, bookPrice = ?, bookSynopsis = ?, bookDatePublished = ?, bookStatus = ? WHERE bookID = ?");
    $stmt->bind_param('ssssssss', $title, $author, $category, $price, $synopsis, $datePublished, $status, $bookID);
    $stmt->execute();

    header('Location: StaffBookList.php');
    exit;
}

// Prepare and execute the select statement to fetch the book details
$stmt = $conn->prepare("SELECT * FROM book WHERE bookID = ?");
$stmt->bind_param('s', $bookID);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();

// Check if the book exists
if (!$book) {
    echo "Book not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Edit Book</h1>
        <form action="StaffEditBook.php?bookID=<?php echo htmlspecialchars($bookID); ?>" method="post">
            <input type="hidden" name="bookID" value="<?php echo htmlspecialchars($book['bookID']); ?>">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($book['bookTitle']); ?>">
            </div>
            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" id="author" name="author" class="form-control" value="<?php echo htmlspecialchars($book['bookAuthor']); ?>">
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <input type="text" id="category" name="category" class="form-control" value="<?php echo htmlspecialchars($book['bookCategory']); ?>">
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" id="price" name="price" class="form-control" value="<?php echo htmlspecialchars($book['bookPrice']); ?>">
            </div>
            <div class="form-group">
                <label for="synopsis">Synopsis:</label>
                <textarea id="synopsis" name="synopsis" class="form-control"><?php echo htmlspecialchars($book['bookSynopsis']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="datePublished">Date Published:</label>
                <input type="date" id="datePublished" name="datePublished" class="form-control" value="<?php echo htmlspecialchars($book['bookDatePublished']); ?>">
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <input type="text" id="status" name="status" class="form-control" value="<?php echo htmlspecialchars($book['bookStatus']); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</body>
</html>