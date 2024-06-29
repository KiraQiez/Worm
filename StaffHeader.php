<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="rsc/styles.css">
    <link rel="stylesheet" href="rsc/customer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
                        <a href="#">Customer Data</a>
                        <a href="#">Insert Customer</a>
                        <a href="#">Update Customer</a>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#">Staff</a>
                    <i class="fas fa-caret-down"></i>
                    <div class="dropdown-menu">
                        <a href="#">Staff Data</a>
                        <a href="#">Insert Staff</a>
                        <a href="#">Update Staff</a>
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
                <li><a href="#">Report</a></li>
            </ul>
        </div>
        <div class="user-profile">
            <img src="rsc/image/picture.png" alt="Profile Image">
            <div class="dropdown">
                <div class="user-info">
                    <p>Username</p>
                    <p class="rank">Member</p>
                </div>
                <div class="dropdown-menu">
                    <a href="#">Profile</a>
                    <a href="#">Settings</a>
                    <a href="MainLogout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>