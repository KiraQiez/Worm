<?php
$title = "Add Book";
include 'StaffHeader.php';
include 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookID = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $synopsis = $_POST['synopsis'];
    $datePublished = $_POST['datePublished'];
    $status = $_POST['status'];
    
    $image = null;
    if (!empty($_FILES['image']['tmp_name'])) {
        $fileSize = $_FILES['image']['size'];
        if ($fileSize < 16777215) { // Check if file size is less than 16MB (LONGBLOB max size)
            $image = file_get_contents($_FILES['image']['tmp_name']);
        } else {
            echo "File size exceeds the maximum limit of 16MB.";
            exit;
        }
    }

    $stmt = $conn->prepare("INSERT INTO book (bookID, bookTitle, bookAuthor, bookCategory, bookPrice, bookSynopsis, bookDatePublished, bookStatus, bookImage) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sssssssss', $bookID, $title, $author, $category, $price, $synopsis, $datePublished, $status, $image);
    $stmt->execute();

    header('Location: StaffBookList.php');
    exit;
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
</head>
<body>
    <div class="main-content">
        <div class="content">
            <h1>ADD BOOKS</h1>
            <div class="container">
                <form action="StaffAddBook.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="image">Book Image</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="title">Book Title</label>
                        <input type="text" name="title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="author">Author</label>
                        <input type="text" name="author" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select name="category" id="category" class="form-control">
                            <option value="Fiction">Fiction</option>
                            <option value="Non-Fiction">Non-Fiction</option>
                            <option value="Action">Action</option>
                            <option value="Business">Business</option>
                            <option value="Romance">Romance</option>
                            <option value="SciFi">SciFi</option>
                            <option value="Mystery">Mystery</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id">Book ID</label>
                        <input type="text" name="id" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="text" name="price" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="synopsis">Synopsis</label>
                        <textarea name="synopsis" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="datePublished">Date Published</label>
                        <input type="date" name="datePublished" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <input type="text" name="status" class="form-control">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">ADD</button>
                        <a href="StaffBookList.php" class="btn btn-secondary">BACK</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>