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

<div class="headerbg">
    <div class="header-content">
        <p>Explore a world of knowledge with our extensive collection of books. Enjoy exclusive discounts and free shipping for members. Join now and start your reading journey!</p>
        <?php
        if (isset($_SESSION['usertype'])) {
            if ($_SESSION['usertype'] == 'customer') {
                echo '<a class="btn btn-primary" href="CustomerLibrary.php">Get Started</a>';
            } else if ($_SESSION['usertype'] == 'staff') {
                echo '<a class="btn btn-primary" href="StaffLibrary.php">Get Started</a>';
            } else {
                echo '<a class="btn btn-primary" href="MainLogin.php">Get Started</a>';
            }
        } else {
            echo '<a class="btn btn-primary" href="MainLogin.php">Get Started</a>';
        }
        ?>
    </div>
</div>
<div class="home-content">
    <div class="home-container">
        <h2>Quotes</h2>
        <div class="quotes">
            <div class="quotes-container">
                <div class="quote-item quote-item-1">
                    <div class="quote-card">
                        <p class="quote-text">"The more that you read, the more things you will know. The more that you learn, the more places you'll go."</p>
                        <p class="quote-author">- Dr. Seuss</p>
                    </div>
                </div>
                <div class="quote-item quote-item-2">
                    <div class="quote-card">
                        <p class="quote-text">"A reader lives a thousand lives before he dies. The man who never reads lives only one."</p>
                        <p class="quote-author">- George R.R. Martin</p>
                    </div>
                </div>
                <div class="quote-item quote-item-3">
                    <div class="quote-card">
                        <p class="quote-text">"Books are a uniquely portable magic."</p>
                        <p class="quote-author">- Stephen King</p>
                    </div>
                </div>
                <div class="quote-item quote-item-4">
                    <div class="quote-card">
                        <p class="quote-text">"There is no friend as loyal as a book."</p>
                        <p class="quote-author">- Ernest Hemingway</p>
                    </div>
                </div>
                <div class="quote-item quote-item-5">
                    <div class="quote-card">
                        <p class="quote-text">"A room without books is like a body without a soul."</p>
                        <p class="quote-author">- Marcus Tullius Cicero</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const items = document.querySelectorAll('.quote-item');
        let current = 2;
        const totalItems = items.length;

        function moveNext() {
            items.forEach((item, index) => {
                let newClass = `quote-item-${((index - current + totalItems) % totalItems) + 1}`;
                item.className = `quote-item ${newClass}`;
            });
            current = (current + 1) % totalItems;
        }

        function movePrev() {
            current = (current - 1 + totalItems) % totalItems;
            items.forEach((item, index) => {
                let newClass = `quote-item-${((index - current + totalItems) % totalItems) + 1}`;
                item.className = `quote-item ${newClass}`;
            });
        }

        setInterval(moveNext, 3000);
    });
</script>
</body>

</html>