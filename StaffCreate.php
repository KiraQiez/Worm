<?php
include 'db.php'; // Include your database connection
$title = "Insert Staff"; // Title of the page
include 'StaffHeader.php'; // Include header HTML

// Function to generate a new user ID
function generate_staff_id($conn)
{
    $sql = "SELECT userid FROM system_users WHERE userid LIKE 'S%' ORDER BY userid DESC LIMIT 1;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_id = $row['userid'];
        $num = intval(substr($last_id, 1)) + 1;
        return 'S' . str_pad($num, 3, '0', STR_PAD_LEFT);
    } else {
        return 'S001';
    }
}

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input values
    $username = htmlspecialchars($_POST["username"]);
    $fullname = htmlspecialchars($_POST["fullname"]);
    $email = htmlspecialchars($_POST["email"]);
    $gender = htmlspecialchars($_POST["gender"]);
    $password = htmlspecialchars($_POST["password"]);
    $usertype = 'staff'; // Staff type is fixed as 'staff'
    $stafftype = htmlspecialchars($_POST["stafftype"]);

    // Generate a new staff ID
    $userid = generate_staff_id($conn);

    // Check for empty fields
    if (empty($username) || empty($fullname) || empty($email) || empty($gender) || empty($password) || empty($stafftype)) {
        $message = "All fields are required.";
    } else {
        // Check if the username or email already exists
        $sql = "SELECT * FROM system_users WHERE username='$username' OR email='$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $message = "Username or email already exists.";
        } else {
            // Insert into system_users table
            $sql1 = "INSERT INTO system_users (userid, username, fullname, email, gender, password, usertype) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bind_param("sssssss", $userid, $username, $fullname, $email, $gender, $password, $usertype);

            // Insert into staff table
            $sql2 = "INSERT INTO staff (staffid, stafftype) VALUES (?, ?)";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("ss", $userid, $stafftype);

            if ($stmt1->execute() && $stmt2->execute()) {
                $message = "Staff created successfully.";
            } else {
                $message = "Error: " . $conn->error;
            }

            $stmt1->close();
            $stmt2->close();
        }
    }

    $conn->close();
}
?>

<body>
    <div class="container1">
        <h2>Create Staff</h2>
        <?php if (!empty($message)) : ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" action="StaffCreate.php">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                <label for="username">Username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Full Name" required>
                <label for="fullname">Full Name</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="gender" name="gender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="M">Male</option>
                    <option value="F">Female</option>
                </select>
                <label for="gender">Gender</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="stafftype" name="stafftype" required>
                    <option value="" disabled selected>Select Staff Type</option>
                    <option value="manager">Manager</option>
                    <option value="employee">Employee</option>
                </select>
                <label for="stafftype">Staff Type</label>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="primary">Create</button>
                <button type="button" onclick="window.location.href = 'StaffRead.php'" class="delete">Back</button>
            </div>
        </form>
    </div>
</body>
</html>
