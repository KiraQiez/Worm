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
    $status = htmlspecialchars($_POST["status"]);

    // Check if username and email are the same
    if ($username === $email) {
        echo "Error: Username and email cannot be the same.";
    } else {
        // Update query
        $sql = "UPDATE system_users 
                INNER JOIN customer ON system_users.userid = customer.custid
                SET system_users.username = ?, system_users.fullname = ?, system_users.gender = ?, 
                    system_users.email = ?, system_users.password = ?, system_users.usertype = ?, 
                    customer.status = ?
                WHERE system_users.userid = ?";

        // Prepare statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssssss", $username, $fullname, $gender, $email, $password, $usertype, $status, $userid);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to the main page or a success page
                header("Location: customerData.php?update=success");
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
                       system_users.email, system_users.password, system_users.usertype, customer.status 
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }
        
        .container {
            width:500px;
            height: 100px;
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
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Customer</h2>
        <div class="id-box">ID parameter received: <?php echo $userid; ?></div>
        <form action="customerUpdate.php" method="POST">
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
                <input type="text" class="form-control" id="gender" name="gender" value="<?php echo $gender; ?>" required>
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
                <input type="text" class="form-control" id="usertype" name="usertype" value="<?php echo $usertype; ?>" required>
                <label for="usertype">User Type</label>
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
                <button type="submit" class="btn btn-primary me-2">Update</button>
                <a href="CustomerRead.php" class="btn btn-secondary">Cancel</a>
            </div>

        </form>
    </div>
</body>