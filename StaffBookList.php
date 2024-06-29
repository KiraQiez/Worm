<?php
$title = "Book List";
include 'StaffHeader.php';
include 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fetch books from the database
$books = [];
$sql = "SELECT * FROM book";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = [
            'bookID' => $row['bookID'],
            'title' => $row['bookTitle'],
            'author' => $row['bookAuthor'],
            'category' => $row['bookCategory'],
            'price' => $row['bookPrice'],
            'synopsis' => $row['bookSynopsis'],
            'datePublished' => $row['bookDatePublished'],
            'status' => $row['bookStatus'],
            'image' => $row['bookImage']
        ];
    }
}

$selectedCategories = isset($_GET['categories']) ? explode(',', $_GET['categories']) : [];

function displayBooks($books, $selectedCategories) {
    if (empty($selectedCategories)) {
        return $books;
    }

    return array_filter($books, function($book) use ($selectedCategories) {
        return in_array($book['category'], $selectedCategories);
    });
}

$filteredBooks = displayBooks($books, $selectedCategories);
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
        <div class="sidebar">
            <h4>Categories</h4>
            <hr>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="Fiction" id="fiction" <?php echo in_array('Fiction', $selectedCategories) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="fiction">
                    Fiction
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="Non-Fiction" id="nonFiction" <?php echo in_array('Non-Fiction', $selectedCategories) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="nonFiction">
                    Non-Fiction
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="Mystery" id="mystery" <?php echo in_array('Mystery', $selectedCategories) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="mystery">
                    Mystery
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="Romance" id="romance" <?php echo in_array('Romance', $selectedCategories) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="romance">
                    Romance
                </label>
            </div>
        </div>

        <div class="content">
            <div class="Category-list">
                <?php foreach ($selectedCategories as $category): ?>
                    <button class="category-remove" data-category="<?php echo $category; ?>"><i class="fas fa-times" style="color:#FF5751;"></i> <?php echo $category; ?></button>
                <?php endforeach; ?>
            </div>

            <div class="book-list">
                <h4><?php echo empty($selectedCategories) ? 'All Books' : 'Books in Selected Categories'; ?></h4>
                <?php if (empty($filteredBooks)): ?>
                    <p>No books available in the selected category.</p>
                <?php else: ?>
                    <div class="book-grid">
                        <?php foreach ($filteredBooks as $book): ?>
                            <div class="book">
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($book['image']); ?>" alt="Book Image">
                                <p class="book-title"><?php echo $book['title']; ?></p>
                                <p class="book-author"><?php echo $book['author']; ?></p>
                                <p class="book-price"><?php echo $book['price']; ?></p>
                                <p class="book-synopsis"><?php echo $book['synopsis']; ?></p>
                                <button onclick="window.location.href='StaffEditBook.php?bookID=<?php echo $book['bookID']; ?>'">Edit</button>
                                <button onclick="deleteBook('<?php echo $book['bookID']; ?>')">Delete</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
    document.querySelectorAll('.form-check-input').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            var selectedCategories = [];
            document.querySelectorAll('.form-check-input:checked').forEach(function(checkedBox) {
                selectedCategories.push(checkedBox.value);
            });
            window.location.href = '?categories=' + selectedCategories.join(',');
        });
    });

    document.querySelectorAll('.category-remove').forEach(function(button) {
        button.addEventListener('click', function() {
            var categoryToRemove = this.getAttribute('data-category');
            var selectedCategories = <?php echo json_encode($selectedCategories); ?>;
            var index = selectedCategories.indexOf(categoryToRemove);
            if (index !== -1) {
                selectedCategories.splice(index, 1);
            }
            window.location.href = '?categories=' + selectedCategories.join(',');
        });
    });

    function deleteBook(bookID) {
        if (confirm('Are you sure you want to delete this book?')) {
            window.location.href = 'StaffDeleteBook.php?bookID=' + bookID;
        }
    }
    </script>
</body>
</html>