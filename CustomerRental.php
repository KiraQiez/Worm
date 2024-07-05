<?php
$title = "Rent";
include 'CustomerHeader.php';
// Fetch books from the database
$sql = "SELECT bookTitle, bookAuthor, bookImage FROM book";
$result = $conn->query($sql);
?>

<div class="main-content d-flex">
    <div class="sidebar dashboard">
        <h4>Menu</h4>
        <hr>
        <ul>
            <li><a href="CustomerDashboard.php" ><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="CustomerRental.php" class="active"><i class="fas fa-book"></i> Rental Books</a></li>
            <li><a href="CustomerHistory.php"><i class="fas fa-history"></i> My History</a></li>
        </ul>
    </div>
    <div class="rent-content">
        <div class="rent mb-4">
            <h2>On Rent</h2>
            <hr>
            <div class="rent-list" >
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="rent-card">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['bookImage']) . '" alt="' . $row['bookTitle'] . '">';
                        echo '<h3 class="title">' . $row['bookTitle'] . '</h3>';
                        echo '<p class="author">' . $row['bookAuthor'] . '</p>';
                        echo '<p class="due"> Due: 24/7/2024 </p>';
                        echo '</div>';
                    }
                }
                ?>

                <a href="CustomerCatalogue.php" class="add-rent-link">
                    <div class="add-rent">
                        <i class="fas fa-plus"></i>
                        <div class="cc">
                            <p>Borrow More Books</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

</body>

</html>
<?php
$conn->close();
?>