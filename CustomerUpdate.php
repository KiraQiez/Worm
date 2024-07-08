<?php
// Start output buffering
ob_start();

include 'db.php'; // Include your database connection
$title = "Customer Update"; // Title of the page
include 'StaffHeader.php'; // Include header HTML

// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input values
    $userid = htmlspecialchars($_POST["userid"]);
    $status = htmlspecialchars($_POST["status"]);

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Update customer table
        $sql2 = "UPDATE customer 
                SET status = ? 
                WHERE custid = ?";

        // Prepare statement for customer
        if ($stmt2 = $conn->prepare($sql2)) {
            // Bind variables to the prepared statement as parameters
            $stmt2->bind_param("ss", $status, $userid);

            // Execute the statement
            $stmt2->execute();

            // Close statement
            $stmt2->close();
        } else {
            throw new Exception("Error preparing statement for customer: " . $conn->error);
        }

        // Commit the transaction
        $conn->commit();

        // Redirect to the main page or a success page
        header("Location: CustomerRead.php?update=success");
        exit();
    } catch (Exception $e) {
        // Rollback the transaction
        $conn->rollback();
        echo "Error: Could not update the records. " . $e->getMessage();
    }

    // Close connection
    $conn->close();
} else {
    // Check if the 'id' parameter exists in the URL
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $userid = htmlspecialchars($_GET['id']);

        // Fetch existing data to prefill the form
        $sql = "SELECT system_users.userid, system_users.username, system_users.fullname, 
                       system_users.gender, system_users.email, system_users.password, 
                       system_users.usertype, customer.status 
                FROM system_users 
                INNER JOIN customer ON system_users.userid = customer.custid 
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
                $usertype = $row['usertype'];
                $status = $row['status'];
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
}
?>

<body>
    <div class="container1">
        <h2>Edit Customer Status</h2>
        <div class="id-box">ID parameter received: <?php echo $userid; ?></div>
        <form id="updateForm" action="CustomerUpdate.php" method="POST">
            <input type="hidden" name="userid" value="<?php echo $userid; ?>">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" readonly>
                <label for="username">Username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $fullname; ?>" readonly>
                <label for="fullname">Full Name</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="gender" name="gender" disabled>
                    <option value="F" <?php if ($gender == 'F') echo 'selected'; ?>>Female</option>
                    <option value="M" <?php if ($gender == 'M') echo 'selected'; ?>>Male</option>
                </select>
                <label for="gender">Gender</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" readonly>
                <label for="email">Email</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" value="<?php echo $password; ?>" readonly>
                <label for="password">Password</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="status" name="status" required>
                    <option value="Active" <?php if ($status == 'Active') echo 'selected'; ?>>Active</option>
                    <option value="Suspend" <?php if ($status == 'Suspend') echo 'selected'; ?>>Suspend</option>
                    <option value="Inactive" <?php if ($status == 'Inactive') echo 'selected'; ?>>Inactive</option>
                </select>
                <label for="status">Status</label>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="primary">Save</button>
                <button type="button" onclick="window.location.href = 'CustomerRead.php'" class="delete">Back</button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('updateForm').addEventListener('submit', function(event) {
            if (!confirm('Are you sure you want to update?')) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>

<?php
// End output buffering and flush the output
ob_end_flush();
?>
