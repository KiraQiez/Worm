<?php
$title = "Catalogue";
include 'db.php'; // Include your database connection file
include 'CustomerHeader.php'; // Include the customer header file

// Initialize variables for form values
$rating = $description = '';
$rentalID = '';

// Get the rentalID from the URL
if (isset($_GET['rentalID'])) {
    $rentalID = $_GET['rentalID'];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $rating = $_POST['rating'];
    $description = $_POST['description'];
    $rentalID = $_POST['rentalID'];

    // Prepare SQL statement to insert data
    $query = "INSERT INTO feedback (Rating, Description, RentalID) 
              VALUES ('$rating', '$description', '$rentalID')";

    // Execute query
    if (mysqli_query($conn, $query)) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Feedback submitted!',
                    text: 'Redirecting to your dashboard...',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    location.href = 'CustomerDashboard.php';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Feedback submission failed. Please try again later.',
                    showConfirmButton: true
                });
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
    <link rel="stylesheet" href="feedbackform.css">
    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <div class="main-content">
        <div class="content">
            <h1>FEEDBACK FORM</h1>
            <form action="CustomerFeedbackForm.php" method="post">
                <input type="hidden" name="rentalID" value="<?php echo htmlspecialchars($rentalID); ?>">
                <select name="rating" required>
                    <option value="" disabled selected>Rate your experience</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                <textarea name="description" placeholder="Leave your comment" required></textarea>
                <div class="buttons">
                    <button type="reset" class="reset-btn">Reset</button>
                    <button type="submit" class="send-btn">Send</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</body>
</html>
