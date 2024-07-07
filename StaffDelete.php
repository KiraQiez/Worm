<?php
include 'db.php'; // Include your database connection
$title = "Customer Data"; // Title of the page
include 'StaffHeader.php'; // Include header HTML

// Check if the 'id' parameter exists in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $userid = htmlspecialchars($_GET['id']);

    // Fetch existing data to show the details before deletion
    $sql = "SELECT system_users.userid, system_users.username, system_users.fullname, system_users.email, staff.stafftype 
            FROM system_users 
            INNER JOIN staff ON system_users.userid = staff.staffid 
            WHERE system_users.userid = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $userid);

        // Attempt to execute the prepared statement
        $stmt->execute();

        // Fetch the result
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $username = $row['username'];
            $fullname = $row['fullname'];
            $email = $row['email'];
            $stafftype = $row['stafftype'];
        } else {
            echo "No records found for the provided ID.";
            exit();
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error: Could not prepare the select query. " . $conn->error;
        exit();
    }
} else {
    echo "Error: Invalid ID parameter.";
    exit();
}

// Handle the form submission for deletion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Begin transaction
    $conn->begin_transaction();

    try {
        // Delete from staff table first
        $sql1 = "DELETE FROM staff WHERE staffid = ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("s", $userid);
        $stmt1->execute();
        $stmt1->close();

        // Then delete from system_users table
        $sql2 = "DELETE FROM system_users WHERE userid = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("s", $userid);
        $stmt2->execute();
        $stmt2->close();

        // Commit transaction
        $conn->commit();

        // Redirect to the list of staff
        header("Location: StaffRead.php?delete=success");
        exit();
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo "Error: Could not execute the delete queries. " . $e->getMessage();
    }

    // Close connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Staff</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <script>
        function confirmDelete(event) {
            if (!confirm('Are you sure you want to delete this staff member?')) {
                event.preventDefault();
            }
        }
    </script>
</head>
<body>
    <div class="container1">
        <h2>Delete Staff</h2>
        <div class="id-box">ID parameter received: <?php echo $userid; ?></div>
        <div class="mb-3">
            <strong>Username:</strong> <?php echo $username; ?>
        </div>
        <div class="mb-3">
            <strong>Full Name:</strong> <?php echo $fullname; ?>
        </div>
        <div class="mb-3">
            <strong>Email:</strong> <?php echo $email; ?>
        </div>
        <div class="mb-3">
            <strong>Staff Type:</strong> <?php echo $stafftype; ?>
        </div>
        <form action="StaffDelete.php?id=<?php echo $userid; ?>" method="POST" onsubmit="confirmDelete(event)">
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-danger me-2">Delete</button>
                <a href="StaffRead.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
