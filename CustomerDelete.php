<?php
session_start();
ob_start(); // Start output buffering

include 'db.php'; // Include your database connection
$title = "Customer Delete"; // Title of the page
include 'StaffHeader.php'; // Include header HTML

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $userid = htmlspecialchars($_GET['id']);

    // Fetch existing customer data
    $sql = "SELECT system_users.userid, system_users.username, system_users.fullname, 
                   system_users.email, system_users.gender, system_users.usertype, customer.status 
            FROM system_users 
            INNER JOIN customer ON system_users.userid = customer.custid 
            WHERE system_users.userid = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $userid);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $username = $row['username'];
            $fullname = $row['fullname'];
            $email = $row['email'];
            $gender = $row['gender'];
            $usertype = $row['usertype'];
            $status = $row['status'];
        } else {
            echo "No records found for the provided ID.";
            exit();
        }

        $stmt->close();
    } else {
        echo "Error: Could not prepare the select query. " . $conn->error;
        exit();
    }
} else {
    echo "Error: Invalid ID parameter.";
    exit();
}

// Handle delete confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm'])) {
    // Begin transaction
    $conn->begin_transaction();

    try {
        // Delete from payment table first
        $sql1 = "DELETE FROM payment WHERE rentalID IN (SELECT rentalID FROM rental WHERE custID = ?)";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("s", $userid);
        $stmt1->execute();
        $stmt1->close();

        // Delete from rental table
        $sql2 = "DELETE FROM rental WHERE custID = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("s", $userid);
        $stmt2->execute();
        $stmt2->close();

        // Delete from customer table
        $sql3 = "DELETE FROM customer WHERE custid = ?";
        $stmt3 = $conn->prepare($sql3);
        $stmt3->bind_param("s", $userid);
        $stmt3->execute();
        $stmt3->close();

        // Delete from system_users table
        $sql4 = "DELETE FROM system_users WHERE userid = ?";
        $stmt4 = $conn->prepare($sql4);
        $stmt4->bind_param("s", $userid);
        $stmt4->execute();
        $stmt4->close();

        // Commit transaction
        $conn->commit();

        // Redirect to the customer data page with success message
        header("Location: CustomerRead.php?delete=success");
        exit();
    } catch (mysqli_sql_exception $exception) {
        // Rollback transaction
        $conn->rollback();

        echo "Error: Could not execute the delete query. " . $exception->getMessage();
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
    <title>Delete Customer</title>
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
        <h2>Delete Customer</h2>
        <div class="id-box">ID: <?php echo htmlspecialchars($userid); ?></div>
        <form method="POST" action="CustomerDelete.php?id=<?php echo htmlspecialchars($userid); ?>" onsubmit="return confirmDelete()">
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
                <label for="gender" class="form-label"><strong>Gender :</strong> <?php echo htmlspecialchars($gender == 'M' ? 'Male' : 'Female'); ?></label>
            </div>
            <div class="mb-3">
                <label for="user type" class="form-label"><strong>User Type:</strong> <?php echo htmlspecialchars($usertype); ?></label>
            </div>
            <div class="mb-3">
            <label for="status" class="form-label"><strong>Status:</strong> <?php echo htmlspecialchars($status); ?></label>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" name="confirm" class="btn btn-delete">Delete</button>
                <button type="button" onclick="window.location.href = 'CustomerRead.php'" class="btn btn-cancel">Cancel</button>
            </div>
        </form>
    </div>
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this customer?');
        }
    </script>
</body>
</html>

<?php
$conn->close();
ob_end_flush(); // Flush the output buffer and send output to browser
?>
