<?php
$title = "Library";
include 'CustomerHeader.php';

$books = [
    ['title' => 'Fiction Book 1', 'author' => 'Author 1', 'category' => 'Fiction'],
    ['title' => 'Non-Fiction Book 1', 'author' => 'Author 2', 'category' => 'Non-Fiction'],
    ['title' => 'Mystery Book 1', 'author' => 'Author 3', 'category' => 'Mystery'],
    ['title' => 'Romance Book 1', 'author' => 'Author 4', 'category' => 'Romance'],
    ['title' => 'Fiction Book 2', 'author' => 'Author 5', 'category' => 'Fiction'],
    ['title' => 'Non-Fiction Book 2', 'author' => 'Author 6', 'category' => 'Non-Fiction'],
    ['title' => 'Mystery Book 2', 'author' => 'Author 7', 'category' => 'Mystery'],
    ['title' => 'Romance Book 2', 'author' => 'Author 8', 'category' => 'Romance']
];

$selectedCategories = isset($_GET['categories']) ? explode(',', $_GET['categories']) : [];

function displayBooks($books, $selectedCategories)
{
    if (empty($selectedCategories)) {
        return $books;
    }

    return array_filter($books, function ($book) use ($selectedCategories) {
        return in_array($book['category'], $selectedCategories);
    });
}

$filteredBooks = displayBooks($books, $selectedCategories);
?>

<div class="main-content">
    <div class="sidebar">
        
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
                            <img src="rsc/image/book-default.png" alt="Book Image">
                            <p class="book-title"><?php echo $book['title']; ?></p>
                            <p class="book-author"><?php echo $book['author']; ?></p>
                            <button>View</button>
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