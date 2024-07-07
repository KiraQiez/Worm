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
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this customer?');
        }
    </script>
</head>
<body>
    <div class="container1">
        <h2>Delete Customer</h2>
        <div class="id-box">ID: <?php echo $userid; ?></div>
        <form method="POST" action="CustomerDelete.php?id=<?php echo $userid; ?>" onsubmit="return confirmDelete()">
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
                <a href="CustomerRead.php" class="btn btn-secondary btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
