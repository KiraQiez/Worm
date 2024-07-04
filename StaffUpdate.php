<?php
include 'db.php'; // Include your database connection
$title = "Customer Update"; // Title of the page
include 'StaffHeader.php'; // Include header HTML

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input values
    $userid = htmlspecialchars($_POST["userid"]);
    $username = htmlspecialchars($_POST["username"]);
    $fullname = htmlspecialchars($_POST["fullname"]);
    $gender = htmlspecialchars($_POST["gender"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);
    $usertype = htmlspecialchars($_POST["usertype"]);
    $stafftype = htmlspecialchars($_POST["stafftype"]);

    // Check if username and email are the same
    if ($username === $email) {
        echo "Error: Username and email cannot be the same.";
    } else {
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

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to the main page or a success page
                header("Location: staffData.php?update=success");
                exit();
            } else {
                echo "Error: Could not execute the update query. " . $conn->error;
            }

            // Close statement
            $stmt->close();
        } else {
            echo "Error: Could not prepare the update query. " . $conn->error;
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
    </style>
</head>
<body>
    <div class="container mt-5 form-container">
        <h2>Edit Staff</h2>
        <div class="id-box">ID parameter received: <?php echo $userid; ?></div>
        <form action="StaffUpdate.php" method="POST">
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
                <select class="form-select" id="usertype" name="usertype" required>
                    <option value="admin" <?php if ($usertype == 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="staff" <?php if ($usertype == 'staff') echo 'selected'; ?>>Staff</option>
                </select>
                <label for="usertype">User Type</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="stafftype" name="stafftype" required>
                    <option value="admin" <?php if ($stafftype == 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="manager" <?php if ($stafftype == 'manager') echo 'selected'; ?>>Manager</option>
                    <option value="employee" <?php if ($stafftype == 'employee') echo 'selected'; ?>>Employee</option>
                </select>
                <label for="stafftype">Staff Type</label>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary me-2">Update</button>
                <a href="StaffRead.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
