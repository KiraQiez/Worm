<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$title = "Rental Terms";

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
    <div class="terms-container mt-4">
        <h2 class="mb-4">Rental Terms</h2>
        <ul>
            <li>Fixed rental period is 2 months (60 days, starting from order start date).</li>
            <li>After 60 days, you have 2 days to return the books back to us.</li>
            <li>If you wish to extend the rent to return after due date, a fee of RM3 per month will be charged.</li>
            <li>If you wish to keep the book, kindly inform us, and the deposit will not be refunded.</li>
            <li>Upon return of rentals, and if the books are in good condition without damages, deposit will be duly refunded.</li>
            <li>If books are returned with damages, damage fees will be deducted from deposit. Damage fees may be partial 
                or up to the full amount of the deposit, depending on the extent of damage.</li>
            <li>Book damages include and are not limited to: torn/loose/missing pages and book cover (front and back), markings on pages 
                and cover (writing/doodles/highlights), staining of pages and book cover from any liquid/ink, water/liquid damages, ruined/broken 
                book spine/binding, and any other form of damages that renders the book unusable.</li>
        </ul>
    </div>
</div>
</body>

</html>