<?php
$title = "Rented Books";
include 'StaffHeader.php';
include 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fetch rented books from the database
function fetchRentedBooks($conn, $selectedCategories) {
    $query = "SELECT * FROM book WHERE bookStatus = 'Rented'";
    
    if (!empty($selectedCategories)) {
        $placeholders = implode(',', array_fill(0, count($selectedCategories), '?'));
        $query .= " AND bookCategory IN ($placeholders)";
    }

    $stmt = $conn->prepare($query);

    if (!empty($selectedCategories)) {
        $types = str_repeat('s', count($selectedCategories));
        $stmt->bind_param($types, ...$selectedCategories);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

$selectedCategories = isset($_GET['categories']) ? explode(',', $_GET['categories']) : [];
$rentedBooks = fetchRentedBooks($conn, $selectedCategories);
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
                <h4><?php echo empty($selectedCategories) ? 'All Rented Books' : 'Rented Books in Selected Categories'; ?></h4>
                <?php if (empty($rentedBooks)): ?>
                    <p>No rented books available in the selected category.</p>
                <?php else: ?>
                    <div class="book-grid">
                        <?php foreach ($rentedBooks as $book): ?>
                            <div class="book">
                                <?php if (!empty($book['image'])): ?>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($book['image']); ?>" alt="Book Image">
                                <?php else: ?>
                                    <img src="default_image.jpg" alt="No Image Available">
                                <?php endif; ?>
                                <p class="book-title"><?php echo htmlspecialchars($book['bookTitle']); ?></p>
                                <p class="book-author"><?php echo htmlspecialchars($book['bookAuthor']); ?></p>
                                <p class="book-price"><?php echo htmlspecialchars($book['bookPrice']); ?></p>
                                <p class="book-synopsis"><?php echo htmlspecialchars($book['bookSynopsis']); ?></p>
                                <button onclick="window.location.href='StaffViewBook.php?bookID=<?php echo htmlspecialchars($book['bookID']); ?>'">View</button>
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
    </script>
</body>
</html>