<?php
$title = "Account Suspension";
include 'CustomerHeader.php';
?>
<div class="main-content d-flex justify-content-center align-items-center">
    <div class="card due-card  p-4">
        <h1 class="title text-center mb-4">Account Suspended</h1>
        <img src="rsc/image/due.gif" alt="Due hamster" class="img-fluid mx-auto d-block">
        <p class="text-center mb-3">Your account has been suspended due to overdue books. To regain access, please return the books or pay the outstanding fines.</p>
        <div class="alert alert-danger text-center" role="alert">
            <strong>Important:</strong> Immediate action is required to lift the suspension. You can either return the overdue books or pay the fines associated with them.
        </div>
        <a href="CustomerRent.php" class="btn btn-primary btn-block mb-3">Return Books</a>
    </div>
</div>

</body>

</html>