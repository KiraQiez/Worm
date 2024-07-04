<?php
include 'CustomerHeader.php';
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize input
    $bookID = intval($_POST['bookID']);
    $startRent = $_POST['startRent'];

    // Retrieve custID from session
    $custID = $_SESSION['userid']; // Assuming 'userid' is the session variable storing custID
    $receipt = file_get_contents($_FILES['receipt']['tmp_name']);

    // Calculate rental prices and duration
    $rentalPrice = 5.00;
    $rentalDeposit = getBookPrice($bookID);
    $subtotal = $rentalPrice + $rentalDeposit;
    $endRent = date('Y-m-d', strtotime($startRent . ' + 60 days'));
    $rentalStatus = "out"; // Setting rental status to 'out'

    // Insert into rental table
    $stmt = $conn->prepare("INSERT INTO rental (StartDate, EndDate, RentalStatus, RentalPrice, RentalDeposit, RentalDuration, CustID, BookID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        error_log('mysqli statement prepare error:' . $conn->error);
        die('An error occurred while processing your request.');
    }

    $rentalDuration = 60;
    $stmt->bind_param('sssddisi', $startRent, $endRent, $rentalStatus, $rentalPrice, $rentalDeposit, $rentalDuration, $custID, $bookID);
    if ($stmt->execute() === false) {
        error_log('mysqli statement execute error:' . $stmt->error);
        die('An error occurred while processing your request.');
    }
    $rentalID = $stmt->insert_id;
    $stmt->close();

    // Update book status to 'rented'
    $stmt = $conn->prepare("UPDATE book SET bookStatus = 'Rented' WHERE bookID = ?");
    if ($stmt === false) {
        error_log('mysqli statement prepare error:' . $conn->error);
        die('An error occurred while updating book status.');
    }
    $stmt->bind_param('i', $bookID);
    if ($stmt->execute() === false) {
        error_log('mysqli statement execute error:' . $stmt->error);
        die('An error occurred while updating book status.');
    }
    $stmt->close();

    // Insert into payment table
    $payDate = date('Y-m-d');
    $stmt = $conn->prepare("INSERT INTO payment (PayAmount, PayDate, PayReceipt, RentalID) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        error_log('mysqli statement prepare error:' . $conn->error);
        die('An error occurred while processing your request.');
    }
    $stmt->bind_param('dssi', $subtotal, $payDate, $receipt, $rentalID);
    if ($stmt->execute() === false) {
        error_log('mysqli statement execute error:' . $stmt->error);
        die('An error occurred while processing your request.');
    }
    $stmt->close();

    // Display success message
    echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Payment successful!',
                text: 'Redirecting to Customer Rent page...',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                location.href = 'CustomerRent.php';
            });
          </script>";
}

// Fetch book details for display
if (isset($_GET['bookID']) && isset($_GET['startRent'])) {
    $bookID = $_GET['bookID'];
    $startRent = $_GET['startRent'];

    $stmt = $conn->prepare("SELECT * FROM book WHERE bookID = ?");
    $stmt->bind_param('i', $bookID);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
    $stmt->close();
} else {
    echo ".";
    exit;
}

function getBookPrice($bookID)
{
    global $conn;
    $stmt = $conn->prepare("SELECT bookPrice FROM book WHERE bookID = ?");
    if ($stmt === false) {
        error_log('mysqli statement prepare error:' . $conn->error);
        die('An error occurred while fetching book price.');
    }
    $stmt->bind_param('i', $bookID);
    if ($stmt->execute() === false) {
        error_log('mysqli statement execute error:' . $stmt->error);
        die('An error occurred while fetching book price.');
    }
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
    $stmt->close();
    return $book['bookPrice'];
}
?>

<script>
    function showToast(message) {
        var toastContainer = document.getElementById('toast-container');
        var toast = new bootstrap.Toast(toastContainer);
        document.getElementById('toast-body').innerText = message;
        toast.show();
    }

    function validateForm() {
        var fileInput = document.getElementById('receipt-upload');
        var file = fileInput.files[0];

        if (!file) {
            showToast('Please attach your receipt before submitting.');
            return false; // Prevent form submission
        }

        return true; // Allow form submission
    }
</script>

<div class="payment-content">
    <h1>Payment Details</h1>
    <div class="payment-details">
        <img src="data:image/jpeg;base64,<?php echo base64_encode($book['bookImage']); ?>" alt="Book Image">
        <div class="payment-info">
            <div><strong><?php echo htmlspecialchars($book['bookTitle']); ?></strong></div>
            <div>Rental Duration: 60 Days</div>
            <div>Start Date: <?php echo htmlspecialchars($startRent); ?></div>
            <div>End Date: <?php echo date('Y-m-d', strtotime($startRent . ' + 60 days')); ?></div>
            <div>Deposit: $<?php echo htmlspecialchars($book['bookPrice']); ?></div>
            <div>Rental Price: $5.00</div>
            <div>Subtotal: $<?php echo htmlspecialchars($book['bookPrice'] + 5); ?></div>
        </div>
        <div class="button-container">
            <form action="CustomerPayment.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm();">
                <input type="hidden" name="bookID" value="<?php echo htmlspecialchars($bookID); ?>">
                <input type="hidden" name="startRent" value="<?php echo htmlspecialchars($startRent); ?>">
                <label for="receipt-upload" class="button button-upload">
                    Attach your receipt<input type="file" id="receipt-upload" name="receipt" style="position: absolute; left: -9999px;" required>
                </label>
                <button class="button button-back" type="button" onclick="history.back();">Back</button>
                <button class="button" type="submit">Submit</button>
            </form>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast-container" class="toast align-items-center text-white bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
        <div id="toast-body" class="toast-body">
            <!-- Toast message will appear here -->
        </div>
        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
</div>