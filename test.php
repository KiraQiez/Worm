<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Just Books</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <style>
        .header {
            background-color: #fff;
            padding: 10px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header img {
            height: 50px;
        }

        .nav {
            display: flex;
            align-items: center;
        }

        .nav ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }

        .nav ul li {
            margin-left: 20px;
        }

        .nav ul li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .nav ul li a:hover {
            color: #007bff;
        }

        .search-bar {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-bar input {
            border: 1px solid #ccc;
            border-radius: 20px;
            padding: 5px 10px;
            width: 200px;
        }

        .search-bar button {
            background: none;
            border: none;
            position: absolute;
            right: 10px;
            cursor: pointer;
        }

        .main-content {
            display: flex;
            padding: 20px;
        }

        .sidebar {
            width: 200px;
            padding-right: 20px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 10px;
        }

        .sidebar ul li input {
            margin-right: 10px;
        }

        .content {
            flex-grow: 1;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="d-flex align-items-center">
            <img src="path_to_logo_image" alt="Just Books Logo">
            <div class="search-bar ms-3">
                <input type="text" placeholder="Search...">
                <button><i class="fas fa-search"></i></button>
            </div>
        </div>
        <div class="nav">
            <ul>
                <li><a href="#">Virtual Walkthrough</a></li>
                <li class="dropdown">
                    <a href="#">Genres</a>
                    <i class="fas fa-caret-down"></i>
                    <div class="dropdown-menu">
                        <a href="#">Fiction</a>
                        <a href="#">Non-Fiction</a>
                        <a href="#">Mystery</a>
                        <a href="#">Romance</a>
                        <a href="#">Science Fiction</a>
                        <a href="#">Fantasy</a>
                        <a href="#">Biography</a>
                        <a href="#">History</a>
                        <a href="#">Children's Books</a>
                    </div>
                </li>
                <li><a href="#">Store Locator</a></li>
                <li><a href="#">Login</a></li>
                <li><a href="#"><img src="path_to_google_play_image" alt="Google Play"></a></li>
                <li><a href="#"><img src="path_to_app_store_image" alt="App Store"></a></li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="sidebar">
            <ul>
                <li><input type="checkbox">Auto-Biography</li>
                <li><input type="checkbox">Arts And Crafts</li>
                <li><input type="checkbox">Bengali</li>
                <li><input type="checkbox">Books About India</li>
                <li><input type="checkbox">Business And Management</li>
                <li><input type="checkbox">Business And Marketing</li>
                <li><input type="checkbox">Classics</li>
                <li><input type="checkbox">Comics</li>
                <li><input type="checkbox">Cookery</li>
                <li><input type="checkbox">General</li>
                <li><input type="checkbox">Geo-Politics</li>
                <li><input type="checkbox">Gujarati</li>
                <li><input type="checkbox">Health And Fitness</li>
                <li><input type="checkbox">Hindi</li>
                <li><input type="checkbox">History</li>
                <li><input type="checkbox">Humor</li>
            </ul>
        </div>
        <div class="content">
            <p>Select a Category To view Books</p>
        </div>
    </div>
</body>

</html>