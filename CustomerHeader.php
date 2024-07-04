<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

if (!isset($_SESSION['userid'])) {
    echo "<script>
    alert('You must be logged in to view this page.');
    location.href='MainHomepage.php';
    </script>";
    exit;
}

// Check if the username has changed
$userid = $_SESSION['userid'];
$sql = "SELECT username FROM system_users WHERE userid = '$userid'";
$result = mysqli_query($conn, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    if ($row) {
        $username = $row['username'];
        if ($_SESSION['username'] != $username) {
            $_SESSION['username'] = $username;
        }
    }
}

if ($_SESSION['usertype'] != "customer") {
    echo "<script> 
    alert('You are not authorized to access this page.');
    location.href='MainHomepage.php';
    </script>";
    exit;
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
    <link rel="stylesheet" href="rsc/bookdetails.css">
    <link rel="stylesheet" href="rsc/customer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
                <li><a href="CustomerRent.php">Rent</a></li>
                <li class="dropdown">
                    <a href="CustomerCatalogue.php">Catalogue</a>
                    <i class="fas fa-caret-down"></i>
                    <div class="dropdown-menu">
                        <a href="#">Fiction</a>
                        <a href="#">Non-Fiction</a>
                        <a href="#">Mystery</a>
                        <a href="#">Romance</a>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#">About</a>
                    <i class="fas fa-caret-down"></i>
                    <div class="dropdown-menu">
                        <a href="MainAbout.php">About Us</a>
                        <a href="MainHowWorks.php">How it works</a>
                        <a href="MainTerms.php">Rental Terms</a>
                    </div>
                </li>
                <li><a href="MainContact.php">Contact</a></li>
            </ul>
        </div>
        <div class="user-profile">
            <img src="rsc/image/picture.png" alt="Profile Image">
            <div class="dropdown">
                <div class="user-info">
                    <p><?php echo $_SESSION['username']; ?></p>
                    <p class="rank">Member</p>
                </div>
                <div class="dropdown-menu">
                    <a href="CustomerProfile.php">Profile</a>
                    <a href="MainLogout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>