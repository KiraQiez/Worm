<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$title = "How It Works";

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
        <h2 class="mb-4">How It Works</h2>
        <ul>
            <li>Rent a book for RM5 for a duration of 2 months (60 days).</li>
            <li>Book may be only pickup at the store</li>
            <li>A deposit (refundable after return of book on time and in good condition without damages)
                is charged on every book. Deposit fee varies for each book.</li>
            <li>If you wish to extend and return later, you can do so for RM3 a month (30 days). If you wish to
                keep the book, you may do so, and deposit will not be refunded.</li>
        </ul>
    </div>
</div>
</body>

</html>