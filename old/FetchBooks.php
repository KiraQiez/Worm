<?php
// include 'db.php';

// $selectedCategories = isset($_GET['categories']) ? explode(',', $_GET['categories']) : [];

// function fetchBooks($selectedCategories) {
//     $pdo = getDbConnection();
//     $query = "SELECT * FROM book";
//     if (!empty($selectedCategories)) {
//         $placeholders = implode(',', array_fill(0, count($selectedCategories), '?'));
//         $query .= " WHERE bookCategory IN ($placeholders)";
//     }
//     $stmt = $pdo->prepare($query);
//     foreach ($selectedCategories as $index => $category) {
//         $stmt->bindValue($index + 1, $category);
//     }
//     $stmt->execute();
//     return $stmt->fetchAll();
// }

// $books = fetchBooks($selectedCategories);
?>

<?php
// This part will be included in the StaffBookList.php
function displayBooks($books) {
    foreach ($books as $book) {
        echo '<div class="book">';
        echo '<img src="rsc/image/book-default.png" alt="Book Image">';
        echo '<p class="book-title">' . htmlspecialchars($book['bookTitle']) . '</p>';
        echo '<p class="book-author">' . htmlspecialchars($book['bookAuthor']) . '</p>';
        echo '<p class="book-category">' . htmlspecialchars($book['bookCategory']) . '</p>';
        echo '<p class="book-price">$' . htmlspecialchars($book['bookPrice']) . '</p>';
        echo '<a href="edit_book.php?id=' . htmlspecialchars($book['bookID']) . '" class="edit-btn">Edit</a>';
        echo '<a href="delete_book.php?id=' . htmlspecialchars($book['bookID']) . '" class="delete-btn">Delete</a>';
        echo '</div>';
    }
}
?>