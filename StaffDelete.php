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
    // Delete query for both system_users and staff tables
    $sql = "DELETE FROM system_users WHERE userid = ?";
    $sql2 = "DELETE FROM staff WHERE staffid = ?";

    // Prepare statements
    if ($stmt = $conn->prepare($sql) && $stmt2 = $conn->prepare($sql2)) {
        // Bind variables to the prepared statement as parameters
        $stmt1->bind_param("s", $userid);
        $stmt2->bind_param("s", $userid);

        // Attempt to execute the prepared statements
        if ($stmt1->execute() && $stmt2->execute()) {
            // Redirect to the main page or a success page
            header("Location: staffData.php?delete=success");
            exit();
        } else {
            echo "Error: Could not execute the delete queries. " . $conn->error;
        }

        // Close statements
        $stmt1->close();
        $stmt2->close();
    } else {
        echo "Error: Could not prepare the delete queries. " . $conn->error;
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }

        h2 {
            margin-bottom: 20px;
            color: #343a40;
            font-weight: bold;
            text-align: center;
        }

        .id-box {
            background-color: #e9ecef;
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
            color: #495057;
        }

        .form-container {
            max-width: 500px;
            margin: 0 auto;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>
</head>
<body>
    <div class="container mt-5 form-container">
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
        <form action="StaffDelete.php?id=<?php echo $userid; ?>" method="POST">
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-danger me-2">Delete</button>
                <a href="StaffRead.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
