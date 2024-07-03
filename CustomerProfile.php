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
            <p class="user text-center"><?php echo htmlspecialchars($row['fullname']);
                                        if ($row['gender'] == 'M') {
                                            echo '<span class="badge text-bg-primary ms-2">M</span>';
                                        } else {
                                            echo '<span class="badge text-bg-danger ms-2">F</span>';
                                        } ?>
            </p>
            <div class="ping-container" style="display: none;">
                <div class="ping" id="ping-indicator"></div>
                <div id="ping-message"></div>
            </div>
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
    <div class="read-progress">
        <h4>Read Progress</h4>
        <label>Fiction</label>
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
        </div>
        <label>Non-Fiction</label>
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">50%</div>
        </div>
        <label>Mystery</label>
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75%</div>
        </div>
        <label>Romance</label>
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">100%</div>
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