<?php
    $title = "Catalogue";
    include 'db.php';
    include 'CustomerHeader.php';
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
            <form action="process_feedback.php" method="post">
                <input type="text" name="feedbID" placeholder="Rental id" required>
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
