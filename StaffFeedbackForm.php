<?php
$title = "Staff Customer Feedback";
include 'db.php'; // Include your database connection file
include 'StaffHeader.php'; // Include the staff header file

// Fetch feedback data
$query = "
    SELECT 
        feedback.feedbID,
        feedback.Rating,
        feedback.Description,
        rental.RentalID,
        rental.custid;
        book.bookTitle
    FROM 
        feedback
    JOIN 
        rental ON feedback.RentalID = rental.RentalID
    JOIN 
        book ON rental.bookID = book.BookID
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff View of Customer Feedback</title>
    <link rel="stylesheet" href="styles.css"> <!-- Make sure to have a CSS file for styling -->
</head>
<body>
    <div class="main-content">
        <div class="content">
            <h1>Customer Feedback</h1>
            <table border="1">
                <thead>
                    <tr>
                        <th>Rental ID</th>
                        <th>Customer ID</th>
                        <th>Book Title</th>
                        <th>Rating</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['RentalID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['custID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['bookTitle']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Rating']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Description']) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
