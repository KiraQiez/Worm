<?php
// Start output buffering
ob_start();

include 'db.php'; // Include your database connection
$title = "Staff Update"; // Title of the page
include 'StaffHeader.php'; // Include header HTML

// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input values
    $userid = isset($_POST["userid"]) ? htmlspecialchars($_POST["userid"]) : '';
    $username = isset($_POST["username"]) ? htmlspecialchars($_POST["username"]) : '';
    $fullname = isset($_POST["fullname"]) ? htmlspecialchars($_POST["fullname"]) : '';
    $gender = isset($_POST["gender"]) ? htmlspecialchars($_POST["gender"]) : '';
    $email = isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : '';
    $password = isset($_POST["password"]) ? htmlspecialchars($_POST["password"]) : '';
    $usertype = isset($_POST["usertype"]) ? htmlspecialchars($_POST["usertype"]) : '';
    $stafftype = isset($_POST["stafftype"]) ? htmlspecialchars($_POST["stafftype"]) : '';

    // Check if username and email are the same
    if ($username === $email) {
        echo "Error: Username and email cannot be the same.";
    } else {
        // Start a transaction
        $conn->begin_transaction();

        try {
            // Update query
            $sql = "UPDATE system_users 
                    INNER JOIN staff ON system_users.userid = staff.staffid
                    SET system_users.username = ?, system_users.fullname = ?, system_users.gender = ?, 
                        system_users.email = ?, system_users.password = ?, system_users.usertype = ?, 
                        staff.stafftype = ?
                    WHERE system_users.userid = ?";

            // Prepare statement
            if ($stmt = $conn->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("ssssssss", $username, $fullname, $gender, $email, $password, $usertype, $stafftype, $userid);

                // Execute the statement
                $stmt->execute();

                // Close statement
                $stmt->close();
            } else {
                throw new Exception("Error preparing statement: " . $conn->error);
            }

            // Commit the transaction
            $conn->commit();

            // Redirect to the main page or a success page
            header("Location: staffRead.php?update=success");
            exit();
        } catch (Exception $e) {
            // Rollback the transaction
            $conn->rollback();
            echo "Error: Could not update the records. " . $e->getMessage();
        }

        // Close connection
        $conn->close();
    }
} else {
    // Check if the 'id' parameter exists in the URL
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $userid = htmlspecialchars($_GET['id']);

        // Fetch existing data to prefill the form
        $sql = "SELECT system_users.userid, system_users.username, system_users.fullname, system_users.gender, 
                       system_users.email, system_users.password, system_users.usertype, staff.stafftype 
                FROM system_users 
                INNER JOIN staff ON system_users.userid = staff.staffid 
                WHERE system_users.userid = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $userid);

            // Execute the statement
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
}
?>
<body>
    <div class="container1">
        <h2>Edit Staff</h2>
        <div class="id-box">ID parameter received: <?php echo $userid; ?></div>
        <form id="updateForm" action="StaffUpdate.php" method="POST">
            <input type="hidden" name="userid" value="<?php echo $userid; ?>">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" required>
                <label for="username">Username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $fullname; ?>" required>
                <label for="fullname">Full Name</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="gender" name="gender" required>
                    <option value="M" <?php if ($gender == 'M') echo 'selected'; ?>>Male</option>
                    <option value="F" <?php if ($gender == 'F') echo 'selected'; ?>>Female</option>
                </select>
                <label for="gender">Gender</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
                <label for="email">Email</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" value="<?php echo $password; ?>" required>
                <label for="password">Password</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="stafftype" name="stafftype" required>
                    <option value="manager" <?php if ($stafftype == 'manager') echo 'selected'; ?>>Manager</option>
                    <option value="employee" <?php if ($stafftype == 'employee') echo 'selected'; ?>>Employee</option>
                </select>
                <label for="stafftype">Staff Type</label>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="primary">Save</button>
                <button type="button" onclick="window.location.href = 'StaffRead.php'" class="delete">Back</button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('updateForm').addEventListener('submit', function(event) {
            if (!confirm('Are you sure you want to update?')) {
                event.preventDefault();
            } else {
                // Redirect to StaffRead.php
                window.location.href = 'StaffRead.php';
            }
        });
    </script>
</body>
</html>

<?php
// End output buffering and flush the output
ob_end_flush();
?>
