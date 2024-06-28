<?php
$title = "Profile";
include 'CustomerHeader.php';
?>
<div class="main-content d-flex justify-content-center">
    <div class="profile-container">
        <div class="profile-image">
            <img src="rsc/image/picture.png" alt="Profile Image">
            <p class="user text-center">John Doe</p>
        </div>
        <div class="profile-details">
            <div class="detail-header d-flex">
                <h4>Profile Details</h4>
                <a href="CustomerEditProfile.php" class="edit-link">Edit Profile</a>
            </div>
            <hr>
            <div class="form-group">
                <label for="userid">User ID</label>
                <input type="text" class="form-control" id="userid" value="U001" disabled>
            </div>
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" class="form-control" id="fullname" value="John Doe" disabled>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" value="john.doe@example.com" disabled>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <input type="text" class="form-control" id="gender" value="Male" disabled>
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
</body>

</html>