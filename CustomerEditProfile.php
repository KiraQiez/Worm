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
                <input type="text" class="form-control" id="fullname" value="U001" disabled>
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
</div>
</body>

</html>