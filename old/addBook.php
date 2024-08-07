<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
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
            <h2>Staff</h2>
            <p>aqilhagemaru</p>
        </div>
        <ul class="List">
            <li class="Items"><a href="#Profile"><img src="Items/profile.png" alt="profile">Profile</a></li>
            <li class="Items"><a href="#Dashboard"><img src="Items/dashboard.png" alt="dashboard">Dashboard</a></li>
            <li class="Items" id="bookButton"><a href="#Book"><img src="Items/book.png" alt="book">Book</a></li>            
            <li class="Items"><a href="#Message"><img src="Items/message.png" alt="message">Message</a></li>
            <li class="Items logout"><a href="#Logout"><img src="Items/logout.png" alt="logout">Log Out</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="content">
            <h1>ADD BOOKS</h1>
            <div class="container">
                <div class="item1">
                    test
                </div>
                <div class="item2">
                <form>
                    <input type="file" name="image">
                    <input type="text" name="title" value="Book Title">
                    <select id="type">
                        <option value="Fiction">Fiction</option>
                        <option value="Non-Fiction">Non-Fiction</option>
                        <option value="Action">Action</option>
                        <option value="Business">Business</option>
                        <option value="Romance">Romance</option>
                        <option value="SciFi">SciFi</option>
                        <option value="Mystery">Mystery</option>
                    </select>
                    <input type="text" name="id" value="Book ID">
                    <input type="text" name="rental" value="Rental Price">
                    <input type="text" name="deposit" value="Deposit Price">
                    <input type="text" name="publisher" value="Publisher">
                    <input type="date" name="publish" value="Date Publish">
                    <input type="text" name="desc" value="Description">
                    <button class="back-btn">BACK</button>
                    <button class="add-btn">ADD</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>