<?php
$title = "Book Details";
include 'CustomerHeader.php';
include 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fetch book details
if (isset($_GET['bookID'])) {
    $bookID = $_GET['bookID'];
    $stmt = $conn->prepare("SELECT * FROM book WHERE bookID = ?");
    $stmt->bind_param('i', $bookID);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "No book selected.";
    exit;
}
?>

<div class="main-content d-flex justify-content-center">
    <div class="book-details d-flex justify-content-center">
        <div class="book-image">
            <img src="data:image/jpeg;base64,<?php echo base64_encode($book['bookImage']); ?>" alt="Book Image">
        </div>
        <div class="book-info">
            <h2><b><?php echo htmlspecialchars($book['bookTitle']); ?></b></h2>
            <p><strong>Author:</strong> <?php echo htmlspecialchars($book['bookAuthor']); ?></p>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($book['bookCategory']); ?></p>
            <p><strong>Price:</strong> RM <?php echo htmlspecialchars($book['bookPrice']); ?></p>
            <p><strong>Date Published:</strong> <?php echo htmlspecialchars($book['bookDatePublished']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($book['bookStatus']); ?></p>
            <p><strong>Synopsis:</strong> <?php echo nl2br(htmlspecialchars($book['bookSynopsis'])); ?></p>
            <form id="rentalForm" method="GET" action="CustomerPayment.php">
                <label for="startRent">Start Rent:</label>
                <input type="date" id="startRent" name="startRent" class="form-control" required>
                <input type="hidden" name="bookID" value="<?php echo htmlspecialchars($book['bookID']); ?>">
                <button type="button" class="btn btn-success" onclick="validateAndSubmit()">Checkout</button>
            </form>
        </div>
    </div>
</div>

<script>
    function validateAndSubmit() {
        var startRent = document.getElementById('startRent').value;
        var bookID = "<?php echo $bookID; ?>";

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "CustomerValidateRental.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status == "success") {
                    document.getElementById('rentalForm').submit();
                } else {
                    alert(response.message);
                }
            }
        };
        xhr.send("startRent=" + startRent + "&bookID=" + bookID);
    }
</script>
