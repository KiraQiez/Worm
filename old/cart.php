<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="bookcart.css">
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <img src="Items/logo.jpg" alt="Company Logo">
            <h1>WORM</h1>
        </div>
    </header>
    <div class="sidebar">
        <div class="profile">
            <img src="Items/warden.png" alt="Profile Picture">
            <h2>User</h2>
            <p>aqilhagemaru</p>
        </div>
        <ul class="List">
            <li class="Items"><a href="#Profile"><img src="Items/profile.png" alt="Profile">Profile</a></li>
            <li class="Items"><a href="#Catalogue"><img src="Items/catalogue.png" alt="catalogue">Catalogue</a></li>
            <li class="Items"><a href="#Cart"><img src="Items/cart.png" alt="cart">Cart</a></li>
            <li class="Items"><a href="#Rent"><img src="Items/rent.png" alt="Rent">Rent</a></li>
            <li class="Items"><a href="#Feedback"><img src="Items/message.png" alt="Feedback">Feedback</a></li>
            <li class="Items logout"><a href="#Logout"><img src="Items/logout.png" alt="logout">Log Out</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="container">
            <table>
                <tr>
                    <th></th>
                    <th>PRODUCT</th>
                    <th>TOTAL</th>
                    <th></th>
                </tr>
                <tr>
                    <td><img src="Items/allmal.jpg" alt="Product Image"></td>
                    <td>
                        <p>ALL MARKETERS ARE LIARS</p>
                        <p>Rental Durations: 30 Days</p>
                    </td>
                    <td>RM30.00</td>
                    <td><button class="trash-btn">trash</button></td>
                </tr>
            </table>
            <button class="checkout-btn">CHECKOUT</button>
        </div>
    </div>
</body>
</html>