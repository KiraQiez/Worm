<?php
$title = "Library";
include 'CustomerHeader.php';

?>

<div class="main-content d-flex">
    <div class="sidebar">
        <h4>Categories</h4>
        <hr>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="fiction">
            <label class="form-check-label" for="fiction">
                Fiction
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="nonFiction">
            <label class="form-check-label" for="nonFiction">
                Non-Fiction
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="mystery">
            <label class="form-check-label" for="mystery">
                Mystery
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="romance">
            <label class="form-check-label" for="romance">
                Romance
            </label>
        </div>

    </div>

    <div class="content">
        <div class="Category-list">
            <button id="fiction"><i class="fas fa-times" style="color:#FF5751;"></i> Fiction</button>
            <button id="fiction"><i class="fas fa-times" style="color:#FF5751;"></i> Non-Fiction</button>
        </div>

        <div class=" book-list">
            <h4>Select a Category To View Books</h4>
            <div class="book">
                <img src="rsc/image/book-default.png" alt="Book Image">
                <p class="book-title">Book Title</p>
                <p class="book-author">Author</p>
                <button>View</button>
            </div>
        </div>

    </div>

    </body>

    </html>