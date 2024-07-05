<?php
$title = "Edit Book";
include 'StaffHeader.php';
include 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the bookID is set in the URL
if (!isset($_GET['bookID'])) {
    $_SESSION['errors'][] = "No book ID provided!";
    header('Location: StaffBookList.php');
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

    $_SESSION['errors'] = [];

    // Check if required fields are empty
    if (empty($title) || empty($author) || empty($category) || empty($price) || empty($synopsis) || empty($datePublished) || empty($status)) {
        $_SESSION['errors'][] = "All fields are required.";
    }

    // Check if price is a valid decimal value
    if (!is_numeric($price)) {
        $_SESSION['errors'][] = "Price must be a valid number.";
    }

    if (!empty($_SESSION['errors'])) {
        header("Location: StaffEditBook.php?bookID=$bookID");
        exit;
    }

    $image = null;
    if (!empty($_FILES['image']['tmp_name'])) {
        $fileSize = $_FILES['image']['size'];
        if ($fileSize < 16777215) { // Check if file size is less than 16MB (LONGBLOB max size)
            $image = file_get_contents($_FILES['image']['tmp_name']);
        } else {
            $_SESSION['errors'][] = "File size exceeds the maximum limit of 16MB.";
            header("Location: StaffEditBook.php?bookID=$bookID");
            exit;
        }
    }

    if ($image) {
        $stmt = $conn->prepare("UPDATE book SET bookTitle = ?, bookAuthor = ?, bookCategory = ?, bookPrice = ?, bookSynopsis = ?, bookDatePublished = ?, bookStatus = ?, bookImage = ? WHERE bookID = ?");
        $stmt->bind_param('sssssssss', $title, $author, $category, $price, $synopsis, $datePublished, $status, $image, $bookID);
    } else {
        $stmt = $conn->prepare("UPDATE book SET bookTitle = ?, bookAuthor = ?, bookCategory = ?, bookPrice = ?, bookSynopsis = ?, bookDatePublished = ?, bookStatus = ? WHERE bookID = ?");
        $stmt->bind_param('ssssssss', $title, $author, $category, $price, $synopsis, $datePublished, $status, $bookID);
    }

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
    $_SESSION['errors'][] = "Book not found!";
    header('Location: StaffBookList.php');
    exit;
}
?>

<div class="main-content">
    <div class="content">
        <h1>Edit Book</h1>
        <div class="container">
            <form action="StaffEditBook.php?bookID=<?php echo htmlspecialchars($bookID); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="bookID" value="<?php echo htmlspecialchars($book['bookID']); ?>">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="title">Book Title</label>
                        <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($book['bookTitle']); ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="author">Author</label>
                        <input type="text" id="author" name="author" class="form-control" value="<?php echo htmlspecialchars($book['bookAuthor']); ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="category">Category</label>
                        <select id="category" name="category" class="form-control">
                            <option value="Fiction" <?php if ($book['bookCategory'] == 'Fiction') echo 'selected'; ?>>Fiction</option>
                            <option value="Non-Fiction" <?php if ($book['bookCategory'] == 'Non-Fiction') echo 'selected'; ?>>Non-Fiction</option>
                            <option value="Action" <?php if ($book['bookCategory'] == 'Action') echo 'selected'; ?>>Action</option>
                            <option value="Business" <?php if ($book['bookCategory'] == 'Business') echo 'selected'; ?>>Business</option>
                            <option value="Romance" <?php if ($book['bookCategory'] == 'Romance') echo 'selected'; ?>>Romance</option>
                            <option value="SciFi" <?php if ($book['bookCategory'] == 'SciFi') echo 'selected'; ?>>SciFi</option>
                            <option value="Mystery" <?php if ($book['bookCategory'] == 'Mystery') echo 'selected'; ?>>Mystery</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="price">Price</label>
                        <input type="text" id="price" name="price" class="form-control" value="<?php echo htmlspecialchars($book['bookPrice']); ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="Available" <?php if ($book['bookStatus'] == 'Available') echo 'selected'; ?>>Available</option>
                            <option value="Rented" <?php if ($book['bookStatus'] == 'Rented') echo 'selected'; ?>>Rented</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="datePublished">Date Published</label>
                        <input type="date" id="datePublished" name="datePublished" class="form-control" value="<?php echo htmlspecialchars($book['bookDatePublished']); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="synopsis">Synopsis</label>
                    <textarea id="synopsis" name="synopsis" class="form-control"><?php echo htmlspecialchars($book['bookSynopsis']); ?></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="image">Book Image</label>
                        <input type="file" id="image" name="image" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="primary">Save</button>
                    <button type="button" onclick="history.back()" class="delete">Back</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        <?php
        if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
            foreach ($_SESSION['errors'] as $error) {
                echo "toastr.error('" . $error . "');";
            }
            unset($_SESSION['errors']); // Clear errors after displaying
        }
        ?>
    });
</script>
</body>

</html>