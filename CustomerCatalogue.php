<?php
$title = "Library";
include 'CustomerHeader.php';
include 'db.php';

// Fetch books from database that are available
$query = "SELECT * FROM book WHERE LOWER(bookStatus) = 'available'";
$result = $conn->query($query);
$books = $result->fetch_all(MYSQLI_ASSOC);

$selectedCategories = isset($_GET['categories']) ? explode(',', $_GET['categories']) : [];

function displayBooks($books, $selectedCategories)
{
    if (empty($selectedCategories)) {
        return $books;
    }
    return array_filter($books, function ($book) use ($selectedCategories) {
        return in_array($book['bookCategory'], $selectedCategories);
    });
}

$filteredBooks = displayBooks($books, $selectedCategories);

//Check if user have due books and if have set user to suspend
$sql = "SELECT * FROM rental WHERE CustID = '$userid' AND RentalStatus = 'Rented' AND EndDate < CURDATE()";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $sqlUpdate = "UPDATE customer SET Status = 'Suspend' WHERE CustID = '$userid'";
} 
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
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="SciFi" id="scifi" <?php echo in_array('SciFi', $selectedCategories) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="scifi">
                SciFi
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="Mystery" id="mystery" <?php echo in_array('Mystery', $selectedCategories) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="mystery">
                Mystery
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="Children" id="children" <?php echo in_array('Children', $selectedCategories) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="children">
                Children
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
                            <?php if($status == 'Suspend'){
                                echo '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#SuspendModal">View</button>';
                            }
                            else{
                                echo '<a href="CustomerBookDetails.php?bookID=' . $book['bookID'] . '" class="btn btn-primary">View</a>';
                            }?>
                            
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
        </div>
    </div>

    

    <div class="modal fade" id="SuspendModal" tabindex="-1" aria-labelledby="suspendModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body d-flex justify-content-center align-items-center">
                <div class="card p-4">
                    <h1 class="title text-center mb-4">Account Suspended</h1>
                    <img src="rsc/image/due.gif" alt="Due hamster" class="img-fluid mx-auto d-block">
                    <div class="alert alert-danger text-center" role="alert">
                        <strong>Important:</strong> Immediate action is required to lift the suspension. You can must return the overdue books and pay the fines associated with them.
                    </div>
                    <a href="CustomerFine.php" class="btn btn-primary btn-block mb-3">Return Books</a>
                </div>
            </div>
        </div>
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