<?php
$title = "Login Page";
include 'MainHeader.php';
include 'db.php';

$message = "";
$ping = null;

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username)) {
        $message = "Please fill in username";
        $ping = false;
    } else if (empty($password)) {
        $message = "Please fill in password";
        $ping = false;
    } else {
        $sql = "SELECT * FROM system_users WHERE username='$username'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 0) {
            $message = "Username doesn't exist";
            $ping = false;
        } else {
            $row = mysqli_fetch_assoc($result);

            if ($password !== $row['password']) {
                $message = "Password doesn't match";
                $ping = false;
            } else {
                session_start(); // Start session here
                $_SESSION['username'] = $username;
                $_SESSION['usertype'] = $row['usertype'];
                $_SESSION['userid'] = $row['userid'];
                
                $message = "Login successful";
                $ping = true;
                
                echo "<script> 
                setTimeout(function() { location.href = 'MainHomepage.php'; }, 1000); 
                </script>";
            }
        }
    }
}
?>

<div class="login-content">
    <div class="login-container mt-5">
        <h2 class="text-center mb-4">Login</h2>
        <hr>
        <form id="login-form" action="MainLogin.php" method="POST" class="needs-validation" novalidate>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
                <label for="username">Username</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <div class="ping-container" style="display: none;">
                <div class="ping" id="ping-indicator"></div>
                <div id="ping-message" class="ping-text"></div>
            </div>
            <button id="login-button" type="submit" name="submit" class="btn btn-primary btn-block w-100 mt-4">Login</button>
        </form>
        <p class="mt-3 text-center">Don't have an account? <a href="MainRegister.php" class="link">Register</a></p>
    </div>
</div>

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