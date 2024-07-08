<?php
session_start();
ob_start(); // Start output buffering

include 'db.php'; // Include your database connection
$title = "Staff Delete"; // Title of the page
include 'StaffHeader.php'; // Include header HTML

// Ensure usertype is set in the session to avoid undefined index warnings
if (!isset($_SESSION['usertype'])) {
    $_SESSION['usertype'] = null;
}

// Check if the 'id' parameter exists in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $userid = htmlspecialchars($_GET['id']);

    // Check if the logged-in user is trying to delete themselves
    if ($userid == $_SESSION['userid']) {
        echo "<script>alert('Error: You cannot delete your own account.'); location.href='StaffRead.php';</script>";
        exit();
    }

    // Fetch existing data to show the details before deletion
    $sql = "SELECT system_users.userid, system_users.username, system_users.fullname, system_users.gender, system_users.email, system_users.password, staff.stafftype 
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
            $gender = $row['gender'];
            $email = $row['email'];
            $password = $row['password'];
            $stafftype = $row['stafftype'];
        } else {
            echo "<script>alert('No records found for the provided ID.'); location.href='StaffRead.php';</script>";
            exit();
        }

        // Close statement
        $stmt->close();
    } else {
        echo "<script>alert('Error: Could not prepare the select query.'); location.href='StaffRead.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Error: Invalid ID parameter.'); location.href='StaffRead.php';</script>";
    exit();
}

// Handle delete confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm'])) {
    // Begin transaction
    $conn->begin_transaction();

    try {
        // Check if the staff type allows deletion of this user
        if ($_SESSION['usertype'] == 'staff' && $stafftype == 'manager') {
            echo "<script>alert('Error: Managers cannot delete other managers.'); location.href='StaffRead.php';</script>";
            exit();
        }

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
        echo "<script>alert('Staff deleted successfully.'); location.href='StaffRead.php';</script>";
        exit();
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo "<script>alert('Error: Could not execute the delete queries.'); console.error('Error: " . $e->getMessage() . "');</script>";
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
    <style>
        .container1 {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
        .btn-cancel {
            background-color: #6c757d;
            color: white;
        }
        .btn-cancel:hover {
            background-color: #5a6268;
        }
        .id-box {
            padding: 10px;
            margin-bottom: 20px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .form-floating label {
            color: #495057;
        }
        h2 {
            margin-bottom: 20px;
            color: #343a40;
        }
    </style>
</head>
<body>
    <div class="container1">
        <h2>Delete Staff</h2>
        <div class="id-box">ID parameter received: <?php echo htmlspecialchars($userid); ?></div>
        <form method="POST" action="StaffDelete.php?id=<?php echo htmlspecialchars($userid); ?>" onsubmit="return confirmDelete()">
            <div class="mb-3">
                <label for="username" class="form-label"><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></label>
            </div>
            <div class="mb-3">
                <label for="fullname" class="form-label"><strong>Full Name:</strong> <?php echo htmlspecialchars($fullname); ?></label>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label"><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></label>
            </div>
            <div class="mb-3">
                <label for="stafftype" class="form-label"><strong>Staff Type:</strong> <?php echo htmlspecialchars($stafftype); ?></label>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" name="confirm" class="btn btn-delete">Delete</button>
                <button type="button" onclick="window.location.href = 'StaffRead.php'" class="btn btn-cancel">Cancel</button>
            </div>
        </form>
    </div>
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this staff member?');
        }
    </script>
</body>
</html>

<?php
$conn->close();
ob_end_flush(); // Flush the output buffer and send output to browser
?>
