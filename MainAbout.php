<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$title = "About Us";

if (isset($_SESSION['usertype'])) {
    if ($_SESSION['usertype'] == 'customer') {
        include 'CustomerHeader.php';
    } else if ($_SESSION['usertype'] == 'staff') {
        include 'StaffHeader.php';
    } else {
        include 'MainHeader.php';
    }
} else {
    include 'MainHeader.php';
}
?>
<div class="main-content">
    <div class="about-container">
        <h2 class="text-center">Book Your Next Escape</h2>
        <p class="text-center">At Worm, our passion drives us to connect readers with the books they love. Founded with a vision
            to make literature accessible to all, we embark on a journey to revolutionize the way people engage with
            reading materials. We strive to create a world where access to knowledge and literature is effortless,
            enriching lives and fostering a love for reading among individuals of all backgrounds and ages.</p>
        <div class="infoGraphic container my-5">
            <div class="row justify-content-center">
                <div class="col-md-3 col-sm-6 info">
                    <img src="rsc/image/aboutUs-item1.png" alt="About Us Image 1" class="img-fluid">
                    <p>Provide a vast and diverse collection of books spanning various genres and interests.</p>
                </div>
                <div class="col-md-3 col-sm-6 info">
                    <img src="rsc/image/aboutUs-item2.png" alt="About Us Image 2" class="img-fluid">
                    <p>Empower effortless book rentals with streamlined online browsing and reservation tools.</p>
                </div>
                <div class="col-md-3 col-sm-6 info">
                    <img src="rsc/image/aboutUs-item3.png" alt="About Us Image 3" class="img-fluid">
                    <p>Champion local libraries and lifelong learning, fostering a vibrant reading community.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

</html>