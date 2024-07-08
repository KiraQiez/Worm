<?php
$title = "Profile";
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
        </div>
        <div class="profile-details">
            <div class="detail-header d-flex justify-content-between">
                <h4>Profile Details</h4>
                <a href="CustomerEditProfile.php" class="edit-link">Edit Profile</a>
            </div>
            <hr>
            <div class="form-group">
                <label for="userid">User ID</label>
                <input type="text" class="form-control" id="userid" value="<?php echo htmlspecialchars($row['userid']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($row['username']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($row['email']); ?>" disabled>
            </div>
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