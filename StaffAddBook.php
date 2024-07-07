<?php
$title = "Add Book";
include 'StaffHeader.php';
include 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        header('Location: StaffAddBook.php');
        exit;
    }

    $image = null;
    if (!empty($_FILES['image']['tmp_name'])) {
        $fileSize = $_FILES['image']['size'];
        if ($fileSize < 16777215) { // Check if file size is less than 16MB (LONGBLOB max size)
            $image = file_get_contents($_FILES['image']['tmp_name']);
        } else {
            $_SESSION['errors'][] = "File size exceeds the maximum limit of 16MB.";
            header('Location: StaffAddBook.php');
            exit;
        }
    }

    $stmt = $conn->prepare("INSERT INTO book (bookTitle, bookAuthor, bookCategory, bookPrice, bookSynopsis, bookDatePublished, bookStatus, bookImage) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssssss', $title, $author, $category, $price, $synopsis, $datePublished, $status, $image);
    $stmt->execute();

    // Show success message
    echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Book has been added successfully.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'StaffBookList.php';
            });
          </script>";
    exit;
}
?>

<div class="main-content">
    <div class="content">
        <h4>Add Book</h4>
        <div class="container">
            <form action="StaffAddBook.php" method="post" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="title">Book Title</label>
                        <input type="text" name="title" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="author">Author</label>
                        <input type="text" name="author" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="category">Category</label>
                        <select name="category" id="category" class="form-control">
                            <option selected disabled>Category selection</option>
                            <option value="Fiction">Fiction</option>
                            <option value="Children">Children</option>
                            <option value="Non-Fiction">Non-Fiction</option>
                            <option value="Action">Action</option>
                            <option value="Horror">Horror</option>
                            <option value="Romance">Romance</option>
                            <option value="SciFi">SciFi</option>
                            <option value="Mystery">Mystery</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="price">Price</label>
                        <input type="text" name="price" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="Available">Available</option>
                            <option value="Rented">Rented</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="datePublished">Date Published</label>
                        <input type="date" name="datePublished" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="synopsis">Synopsis</label>
                    <textarea name="synopsis" class="form-control"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="image">Book Image</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="primary">ADD</button>
                    <button type="button" onclick="window.location.href='StaffBookList.php'" class="delete">BACK</button>
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
            unset($_SESSION['errors']);
        }
        ?>
    });
</script>
</body>

</html>