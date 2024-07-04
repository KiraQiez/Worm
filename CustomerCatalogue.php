<?php
$title = "Library";
include 'CustomerHeader.php';
include 'db.php';

// Fetch books from database
$query = "SELECT * FROM book";
$result = $conn->query($query);
$books = $result->fetch_all(MYSQLI_ASSOC);

$selectedCategories = isset($_GET['categories']) ? explode(',', $_GET['categories']) : [];

function displayBooks($books, $selectedCategories) {
    if (empty($selectedCategories)) {
        return $books;
    }
    return array_filter($books, function ($book) use ($selectedCategories) {
        return in_array($book['bookCategory'], $selectedCategories);
    });
}

$filteredBooks = displayBooks($books, $selectedCategories);
?>

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
            <input class="form-check-input" type="checkbox" value="Action" id="action" <?php echo in_array('Action', $selectedCategories) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="action">
                Action
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="Horror" id="horror" <?php echo in_array('Horror', $selectedCategories) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="horror">
                Horror
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="Romance" id="romance" <?php echo in_array('Romance', $selectedCategories) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="romance">
                Romance
            </label>
        </div><div class="form-check">
            <input class="form-check-input" type="checkbox" value="SciFi" id="scifi" <?php echo in_array('SciFi', $selectedCategories) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="scifi">
                SciFi
            </label>
        </div><div class="form-check">
            <input class="form-check-input" type="checkbox" value="Mystery" id="mystery" <?php echo in_array('Mystery', $selectedCategories) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="mystery">
                Mystery
            </label>
        </div>
    </div>

    <div class="content">
        <div class="Category-list">
            <?php foreach ($selectedCategories as $category) : ?>
                <button class="category-remove" data-category="<?php echo $category; ?>"><i class="fas fa-times" style="color:#FF5751;"></i> <?php echo $category; ?></button>
            <?php endforeach; ?>
        </div>

        <div class="book-list">
            <h4><?php echo empty($selectedCategories) ? 'All Books' : 'Books in Selected Categories'; ?></h4>
            <?php if (empty($filteredBooks)) : ?>
                <p>No books available in the selected category.</p>
            <?php else : ?>
                <div class="book-grid">
                    <?php foreach ($filteredBooks as $book) : ?>
                        <div class="book">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($book['bookImage']); ?>" alt="Book Image">
                            <p class="book-title"><?php echo $book['bookTitle']; ?></p>
                            <p class="book-author"><?php echo $book['bookAuthor']; ?></p>
                            <a href="CustomerBookDetails.php?bookID=<?php echo $book['bookID']; ?>" class="btn btn-primary">View</a>
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
