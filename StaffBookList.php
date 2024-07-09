<?php
$title = "Book List";
include 'StaffHeader.php';
include 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Pagination settings
$limit = 6; // Number of books per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
$offset = ($page - 1) * $limit; // Offset for SQL query

// Fetch total number of books based on search query
$searchQuery = "";
$searchParam = "";
if (isset($_GET['search'])) {
    $searchParam = $_GET['search'];
    $searchQuery = "WHERE bookID LIKE '%$searchParam%' OR bookTitle LIKE '%$searchParam%'";
}

$totalBooksQuery = "SELECT COUNT(*) AS total FROM book $searchQuery";
$totalBooksResult = $conn->query($totalBooksQuery);
$totalBooks = $totalBooksResult->fetch_assoc()['total'];
$totalPages = ceil($totalBooks / $limit);

// Fetch books from the database with pagination and search query
$books = [];
$sql = "SELECT * FROM book $searchQuery ORDER BY bookCategory ASC LIMIT $limit OFFSET $offset;";
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
?>

<div class="main-content">
    <div class="contentBL">
        <div class="book-list">
            <h4>All Books</h4>
            <form method="get" action="">
                <input type="text" name="search" placeholder="Search by Book ID or Title" class="special-text-input" value="<?php echo htmlspecialchars($searchParam); ?>">
                <button class="secondary" action="submit">Search</button>
            </form>
            <?php if (empty($books)) : ?>
                <p>No books available.</p>
            <?php else : ?>
                <table>
                    <thead>
                        <tr>
                            <th class="title-col">Title</th>
                            <th class="author-col">Author</th>
                            <th class="category-col">Category</th>
                            <th class="price-col">Price</th>
                            <th class="status-col">Status</th>
                            <th class="actions-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $book) : ?>
                            <tr>
                                <td class="title-col"><?php echo htmlspecialchars($book['title']); ?></td>
                                <td class="author-col"><?php echo htmlspecialchars($book['author']); ?></td>
                                <td class="category-col"><?php echo htmlspecialchars($book['category']); ?></td>
                                <td class="price-col">RM <?php echo htmlspecialchars($book['price']); ?></td>
                                <td class="status-col"><?php echo htmlspecialchars($book['status']); ?></td>
                                <td class="actions-col">
                                    <button class="tertiary" onclick="window.location.href='StaffBookDetails.php?bookID=<?php echo htmlspecialchars($book['bookID']); ?>'">View</button>
                                    <button class="primary" onclick="window.location.href='StaffEditBook.php?bookID=<?php echo htmlspecialchars($book['bookID']); ?>'">Edit</button>
                                    <button class="delete" onclick="confirmDelete('<?php echo htmlspecialchars($book['bookID']); ?>')">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <div class="pagination">
                <?php if ($page > 1) : ?>
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo htmlspecialchars($searchParam); ?>" class="prev">Previous</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <a href="?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($searchParam); ?>" class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
                <?php if ($page < $totalPages) : ?>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo htmlspecialchars($searchParam); ?>" class="next">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(bookID) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'StaffDeleteBook.php?bookID=' + bookID;
            }
        });
    }
</script>
</body>
</html>