<?php
$title = "Register Page";
include 'MainHeader.php';
include 'db.php';
?>

<div class="login-content">
    <div class="login-container mt-5">
        <h2 class="text-center mb-4">Register</h2>
        <hr>
        <form id="register-form" action="MainRegister.php" method="POST" class="needs-validation" novalidate>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
                <label for="username">Username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Full name" required>
                <label for="fullname">Full name</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
                <label for="email">Email address</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" name="gender" id="gender" aria-label="Floating label select example" required>
                    <option value="" selected>Not Selected</option>
                    <option value="M">Male</option>
                    <option value="F">Female</option>
                </select>
                <label for="gender">Gender</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <div class="ping-container" style="display: none;">
                <div class="ping" id="ping-indicator"></div>
                <div id="ping-message"></div>
            </div>
            <button id="register-button" type="submit" name="submit" class="btn btn-primary btn-block w-100 mt-4">Register</button>
        </form>
        <p class="mt-3 text-center">Already have an account? <a href="MainLogin.php" class="link">Login</a></p>
    </div>
</div>

<?php
function generate_std_id($conn)
{
    $sql = "SELECT userid FROM system_users WHERE userid LIKE 'U%' ORDER BY userid DESC LIMIT 1;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_id = $row['userid'];
        $num = intval(substr($last_id, 1)) + 1;
        return 'U' . str_pad($num, 3, '0', STR_PAD_LEFT);
    } else {
        return 'U001';
    }
}

$error_message = '';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];

    $userid = generate_std_id($conn);

    if (empty($username)) {
        $message = "Please fill in username";
        $ping = false;
    } else if (empty($password)) {
        $message = "Please fill in password";
        $ping = false;
    } else if (empty($fullname)) {
        $message = "Please fill in full name";
        $ping = false;
    } else if (empty($email)) {
        $message = "Please fill in email";
        $ping = false;
    } else if (empty($gender)) {
        $message = "Please select your gender";
        $ping = false;
    } else {
        $sql = "SELECT * FROM system_users WHERE username='$username'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $message = "Username already exists";
            $ping = false;
        } else {
            $sql = "SELECT * FROM system_users WHERE email='$email'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $message = "Email already exists";
                $ping = false;
            } else {
                $sql = "INSERT INTO system_users (userid, username, fullname, email, gender, password, usertype) VALUES ('$userid', '$username', '$fullname', '$email', '$gender', '$password', 'customer')";
                $sql2 = "INSERT INTO customer (custid, status) VALUES ('$userid', 'active')";

                if ($conn->query($sql) === TRUE && $conn->query($sql2) === TRUE) {
                    $message = "User registered successfully";
                    $ping = true;
                } else {
                    $message = "Failed to register user: " . $conn->error;
                    $ping = false;
                }
            }
        }
    }
}
?>

<script>
    function displayMessage(message, isSuccess) {
        var pingContainer = document.querySelector('.ping-container');
        var pingIndicator = document.getElementById('ping-indicator');
        var pingMessage = document.getElementById('ping-message');

        pingMessage.innerText = message;
        pingIndicator.className = isSuccess ? 'ping ping-success' : 'ping ping-error';
        pingContainer.style.display = 'flex';
    }

    document.addEventListener('DOMContentLoaded', function() {
        <?php if (!empty($message)) : ?>
            displayMessage("<?php echo $message; ?>", <?php echo $ping ? 'true' : 'false'; ?>);
        <?php endif; ?>
    });
</script>
</body>

</html>