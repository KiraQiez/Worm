<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$title = "Homepage";

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

<body>
    <div class="main-content d-flex justify-content-center align-items-center">
        <div class="contact-container">
            <h1 class="title text-center">Contact Us</h1>
            <div class="contact-section d-flex justify-content-between align-items-center">
                <div class="contact-info">
                    <div class="contact-item d-flex align-items-center">
                        <img src="rsc/image/whatsapp.png" alt="WhatsApp" class="contact-icon">
                        <p>017 - 9547236</p>
                    </div>
                    <div class="contact-item d-flex align-items-center">
                        <img src="rsc/image/facebook.png" alt="Facebook" class="contact-icon">
                        <p>BOOKWORMRENTAL</p>
                    </div>
                    <div class="contact-item d-flex align-items-center">
                        <img src="rsc/image/instagram.png" alt="Instagram" class="contact-icon">
                        <p>BOOKWORMRENTAL</p>
                    </div>
                </div>
                <div class="contact-image">
                    <img src="Items/customer_service.png" alt="Customer Service" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</body>

</html>