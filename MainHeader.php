<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="rsc/bootstrap.min.css">
    <link rel="stylesheet" href="rsc/styles.css">
    <link rel="stylesheet" href="rsc/main.css">
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
                    <p>Login Now</p>
                </div>
                <div class="dropdown-menu">
                    <a href="MainLogin.php">Login</a>
                    <a href="MainRegister.php">Register</a>
                </div>
            </div>
        </div>
    </div>