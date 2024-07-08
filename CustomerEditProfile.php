<?php
$title = "Edit Profile";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['usertype'] == 'customer') {
    include 'CustomerHeader.php';
} else {
    include 'StaffHeader.php';
}

$sql = "SELECT * FROM SYSTEM_USERS WHERE USERID = '$_SESSION[userid]'";
$result = mysqli_query($conn, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
} else {
    echo "Error: " . mysqli_error($conn);
    exit;
}

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $oldpassword = $_POST['oldpassword'];
    $newpassword = $_POST['newpassword'];
    $ping = true;

    if (empty($username)) {
        $message = "Please fill in username";
        $ping = false;
    } else if (empty($fullname)) {
        $message = "Please fill in fullname";
        $ping = false;
    } else if (empty($email)) {
        $message = "Please fill in email";
        $ping = false;
    } else if (empty($gender)) {
        $message = "Please select your gender";
        $ping = false;
    } else {
        $sql = "SELECT * FROM SYSTEM_USERS WHERE username='$username' AND USERID != '$_SESSION[userid]'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $message = "Username already exists";
            $ping = false;
        } else {
            $sql = "SELECT * FROM SYSTEM_USERS WHERE email='$email' AND USERID != '$_SESSION[userid]'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $message = "Email already exists";
                $ping = false;
            } else {
                if (!empty($oldpassword) || !empty($newpassword)) {
                    if (empty($newpassword)) {
                        $message = "Please fill in new password";
                        $ping = false;
                    } else if ($oldpassword !== $row['password']) {
                        $message = "Old password doesn't match";
                        $ping = false;
                    } else {
                        $sql = "UPDATE SYSTEM_USERS SET username='$username', fullname='$fullname', email='$email', gender='$gender', password='$newpassword' WHERE USERID='$_SESSION[userid]'";
                        mysqli_query($conn, $sql);
                        $message = "Profile updated successfully";
                        $ping = true;
                        echo "<script> 
                setTimeout(function() { location.href = 'CustomerProfile.php'; }, 1000); 
                </script>";
                    }
                } else {
                    $sql = "UPDATE SYSTEM_USERS SET username='$username', fullname='$fullname', email='$email', gender='$gender' WHERE USERID='$_SESSION[userid]'";
                    mysqli_query($conn, $sql);
                    $message = "Profile updated successfully";
                    $ping = true;
                    echo "<script> 
                setTimeout(function() { location.href = 'CustomerProfile.php'; }, 1000); 
                </script>";
                }
            }
        }
    }
}
?>

<div class="main-content d-flex justify-content-center">
    <div class="profile-container">
        <div class="profile-image text-center mb-3">
            <img src="rsc/image/picture.png" alt="Profile Image" class="rounded-circle mb-2" style="width: 150px; height: 150px;">
            <p class="user text-center">
                <?php echo htmlspecialchars($row['fullname']); ?>
                <?php if ($row['gender'] == 'M') : ?>
                    <span class="badge badge-primary ms-2" style="background-color:#3572EF;">M</span>
                <?php else : ?>
                    <span class="badge badge-danger ms-2" style="background-color:red;">F</span>
                <?php endif; ?>
            </p>
            <div class="ping-container" style="display: none;">
                <div class="ping" id="ping-indicator"></div>
                <div id="ping-message"></div>
            </div>
        </div>
        <div class="profile-details">
            <form method="post" action="">
                <div class="detail-header d-flex justify-content-between">
                    <h4>Profile Details</h4>
                    <button class="edit-link" style="border:none;" type="submit" name="submit">Save Profile</button>
                </div>
                <hr>
                <div class="form-group">
                    <label for="userid">User ID</label>
                    <input type="text" class="form-control" id="userid" value="<?php echo htmlspecialchars($row['userid']); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($row['username']); ?>">
                </div>
                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($row['fullname']); ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>">
                </div>
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select class="form-select" id="gender" name="gender">
                        <option value="M" <?php echo ($row['gender'] == 'M') ? 'selected' : ''; ?>>Male</option>
                        <option value="F" <?php echo ($row['gender'] == 'F') ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="oldpassword">Old Password</label>
                    <input type="password" class="form-control" id="oldpassword" name="oldpassword">
                </div>
                <div class="form-group">
                    <label for="newpassword">New Password</label>
                    <input type="password" class="form-control" id="newpassword" name="newpassword">
                </div>
            </form>
        </div>
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