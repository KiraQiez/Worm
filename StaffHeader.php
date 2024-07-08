<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

// Check if user is not authorized as staff, redirect to homepage if not
if ($_SESSION['usertype'] != "staff") {
    echo "<script>
    alert('You are not authorized to access this page.');
    location.href='MainHomepage.php';
    </script>";
    exit; // Stop further execution
}

// Fetch user data (usertype and staffType)
$userId = $_SESSION['userid'];

// Prepare SQL statement to fetch usertype from system_users
$stmt = $conn->prepare("SELECT usertype FROM system_users WHERE userid = ?");
$stmt->bind_param("s", $userId);
$stmt->execute();
$stmt->bind_result($usertype);
$stmt->fetch();
$stmt->close();

// Prepare SQL statement to fetch staffType from staff table
$stmt2 = $conn->prepare("SELECT stafftype FROM staff WHERE staffid = ?");
$stmt2->bind_param("s", $userId); // Assuming staffid in staff table matches userid in system_users
$stmt2->execute();
$stmt2->bind_result($staffType);
$stmt2->fetch();
$stmt2->close();

// Determine if staff dropdown should be shown based on user's staffType
$showStaffDropdown = ($staffType == 'manager');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WORM</title>
    <link rel="stylesheet" href="rsc/bootstrap.min.css">
    <link rel="stylesheet" href="rsc/styles.css">
    <link rel="stylesheet" href="rsc/main.css">
    <link rel="stylesheet" href="rsc/staff.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flickity/1.0.0/flickity.css"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="header">
        <div class="d-flex align-items-center">
            <img src="rsc/image/logo.svg" alt="INTERIOR Logo">
            <h1 class="ms-2">WORM</h1>
        </div>
        <div class="nav">
            <ul>
                <li><a href="MainHomepage.php">Home</a></li>
                <li class="dropdown">
                    <a href="#">Books</a>
                    <i class="fas fa-caret-down"></i>
                    <div class="dropdown-menu">
                        <a href="StaffBookList.php">Book Data</a>
                        <a href="StaffAddBook.php">Insert Book</a>
                        <a href="StaffRentedBook.php">Rented Book</a>
                        <a href="StaffReturnRequest.php">Return Request</a>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#">Customer</a>
                    <i class="fas fa-caret-down"></i>
                    <div class="dropdown-menu">
                        <a href="CustomerRead.php">Customer Data</a>
                    </div>
                </li>
                <?php if ($showStaffDropdown) : ?>
                    <li class="dropdown">
                        <a href="#">Staff</a>
                        <i class="fas fa-caret-down"></i>
                        <div class="dropdown-menu">
                            <a href="StaffRead.php">Staff Data</a>
                            <a href="StaffCreate.php">Insert Staff</a>
                        </div>
                    </li>
                <?php endif; ?>
                <li><a href="StaffReport.php">Report</a></li>
                <li><a href="StaffFeedbackForm.php">Feedback</a></li>
            </ul>
        </div>
        <div class="user-profile">
            <img src="rsc/image/picture.png" alt="Profile Image">
            <div class="dropdown">
                <div class="user-info">
                    <p><?php echo $_SESSION['username']; ?></p>
                    <p class="rank"><?php echo $_SESSION['usertype'] ?></p>
                </div>
                <div class="dropdown-menu">
                    <a href="CustomerProfile.php">Profile</a>
                    <a href="MainLogout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>