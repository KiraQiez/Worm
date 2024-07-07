<?php
$title = "Catalogue";
include 'db.php'; // Include your database connection file
include 'CustomerHeader.php'; // Include the customer header file

// Initialize variables for form values
$feedbID = $rating = $description = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $feedbID = $_POST['feedbID'];
    $rating = $_POST['rating'];
    $description = $_POST['description'];
    // Assuming RentalID needs to be fetched or set somehow, adjust as per your logic
    $rentalID = $_POST['rentalID'];

    // Prepare SQL statement to insert data
    $query = "INSERT INTO feedback (feedbID, Rating, Description, RentalID) 
              VALUES ('$feedbID', '$rating', '$description', '$rentalID')";

    // Execute query
    if (mysqli_query($conn, $query)) {
        echo "Feedback submitted successfully.";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
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
</head>
<body>
    <div class="main-content">
        <div class="content">
            <h1>FEEDBACK FORM</h1>
            <form action="CustomerDashboard.php" method="post">
                <input type="text" name="feedbID" placeholder="Feedback ID" required>
                <!-- Assuming RentalID is entered by user or fetched from somewhere -->
                <input type="text" name="rentalID" placeholder="Rental ID" required>
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
</body>
</html>
