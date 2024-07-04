<?php
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
        // Delete from customer table first
        $sql1 = "DELETE FROM customer WHERE custid = ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("s", $userid);
        $stmt1->execute();
        $stmt1->close();

        // Delete from system_users table
        $sql2 = "DELETE FROM system_users WHERE userid = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("s", $userid);
        $stmt2->execute();
        $stmt2->close();

        // Commit transaction
        $conn->commit();

        // Redirect to the customer data page with success message
        header("Location: customerData.php?delete=success");
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            margin-bottom: 20px;
            color: #343a40;
            font-weight: bold;
            text-align: center;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
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
    </style>
<body>
    <div class="container">
        <h2>Delete Customer</h2>
        <div class="id-box">ID: <?php echo $userid; ?></div>
        <form method="POST" action="CustomerDelete.php?id=<?php echo $userid; ?>">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" disabled>
                <label for="username">Username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $fullname; ?>" disabled>
                <label for="fullname">Full Name</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" disabled>
                <label for="email">Email</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="gender" name="gender" disabled>
                    <option value="M" <?php if ($gender == 'M') echo 'selected'; ?>>Male</option>
                    <option value="F" <?php if ($gender == 'F') echo 'selected'; ?>>Female</option>
                </select>
                <label for="gender">Gender</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="usertype" name="usertype" value="<?php echo $usertype; ?>" disabled>
                <label for="usertype">User Type</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="status" name="status" value="<?php echo $status; ?>" disabled>
                <label for="status">Status</label>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" name="confirm" class="btn btn-danger me-2">Delete</button>
                <a href="CustomerRead.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
