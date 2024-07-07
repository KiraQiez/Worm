<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

if ($_SESSION['usertype'] != "staff") {
    echo "<script> 
    alert('You are not authorized to access this page.');
    location.href='MainHomepage.php';
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
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
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#">Customer</a>
                    <i class="fas fa-caret-down"></i>
                    <div class="dropdown-menu">
                        <a href="CustomerRead.php">Customer Data</a>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#">Staff</a>
                    <i class="fas fa-caret-down"></i>
                    <div class="dropdown-menu">
                        <a href="StaffRead.php">Staff Data</a>
                        <a href="StaffCreate.php">Insert Staff</a>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#">Transaction</a>
                    <i class="fas fa-caret-down"></i>
                    <div class="dropdown-menu">
                        <a href="#">Transaction Data</a>
                        <a href="#">Insert Transaction</a>
                        <a href="#">Update Transaction</a>
                    </div>
                </li>

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