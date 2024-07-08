<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'db.php'; // Include your database connection

// Ensure usertype is set in the session to avoid undefined index warnings
if (!isset($_SESSION['usertype'])) {
    $_SESSION['usertype'] = null;
}

// Initialize variables
$userid = "";
$username = "";
$fullname = "";
$email = "";
$stafftype = "";

// Check if the 'id' parameter exists in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $userid = htmlspecialchars($_GET['id']);

    // Check if the logged-in user is trying to delete themselves
    if ($userid == $_SESSION['userid']) {
        echo "<script>alert('Error: You cannot delete your own account.'); location.href='StaffRead.php';</script>";
        exit();
    }

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
            echo "<script>alert('No records found for the provided ID.'); location.href='StaffRead.php';</script>";
            exit();
        }

        // Close statement
        $stmt->close();
    } else {
        echo "<script>alert('Error: Could not prepare the select query. " . $conn->error . "'); location.href='StaffRead.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Error: Invalid ID parameter.'); location.href='StaffRead.php';</script>";
    exit();
}

// Handle the form submission for deletion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Begin transaction
    $conn->begin_transaction();

    try {
        // Delete related records from customer table first
        $sql1 = "DELETE FROM customer WHERE custid = ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("s", $userid);
        $stmt1->execute();
        $stmt1->close();

        // Delete from staff table
        $sql2 = "DELETE FROM staff WHERE staffid = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("s", $userid);
        $stmt2->execute();
        $stmt2->close();

        // Then delete from system_users table
        $sql3 = "DELETE FROM system_users WHERE userid = ?";
        $stmt3 = $conn->prepare($sql3);
        $stmt3->bind_param("s", $userid);
        $stmt3->execute();
        $stmt3->close();

        // Commit transaction
        $conn->commit();

        // Redirect to the list of staff
        header("Location: StaffRead.php?delete=success");
        exit();
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo "<script>alert('Error: Could not execute the delete queries. " . $e->getMessage() . "');</script>";
    }

    // Close connection
    $conn->close();
}
?>

<?php
// Include header HTML after the PHP logic to avoid output before header redirect
include 'StaffHeader.php';
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

<?php
// Close connection if still open
if ($conn) {
    $conn->close();
}
?>
