<?php
$title = "Staff Report";
include 'StaffHeader.php';
include 'db.php'; // Include your database connection script
?>
<body>
    <div class="container mt-5">
        <div class="row">
            <!-- Total Book Rental -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Book Rental</h5>
                        <h2 class="card-text"><?php echo getTotalBookRentals(); ?></h2>
                        <p>Today - <?php echo getTodayBookRentals(); ?> This month - <?php echo getThisMonthBookRentals(); ?></p>
                    </div>
                </div>
            </div>
            <!-- Book Available -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Book Available</h5>
                        <h2 class="card-text"><?php echo getAvailableBooks(); ?></h2>
                    </div>
                </div>
            </div>
            <!-- Book Total -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Book Total</h5>
                        <h2 class="card-text"><?php echo getTotalBooks(); ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <!-- Book Categories -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Book Categories</h5>
                        <canvas id="bookCategoriesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <!-- Book Ranking by Month -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Top 3 Book Rentals This Month</h5>
                        <div id="topBooks" class="top-books">
                            <?php
                            $topBooks = getTopBooksByMonth();
                            foreach ($topBooks as $book) {
                                echo '<a href="bookDetails.php?bookID=' . htmlspecialchars($book['bookID']) . '" class="book-item">';
                                echo '<img src="data:image/jpeg;base64,' . base64_encode($book['bookImage']) . '" alt="' . htmlspecialchars($book['bookTitle']) . '" class="book-img">';
                                echo '<div class="book-title">' . htmlspecialchars($book['bookTitle']) . '</div>';
                                echo '</a>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    // Function to establish a database connection
    function getDatabaseConnection() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "wormdb";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

    // Function to get total book rentals
    function getTotalBookRentals() {
        $conn = getDatabaseConnection();
        $sql = "SELECT COUNT(*) as total FROM rental";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $conn->close();
        return $row['total'];
    }

    // Function to get today's book rentals
    function getTodayBookRentals() {
        $conn = getDatabaseConnection();
        $sql = "SELECT COUNT(*) as total FROM rental WHERE DATE(StartDate) = CURDATE()";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $conn->close();
        return $row['total'];
    }

    // Function to get this month's book rentals
    function getThisMonthBookRentals() {
        $conn = getDatabaseConnection();
        $sql = "SELECT COUNT(*) as total FROM rental WHERE MONTH(StartDate) = MONTH(CURDATE()) AND YEAR(StartDate) = YEAR(CURDATE())";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $conn->close();
        return $row['total'];
    }

    // Function to get available books
    function getAvailableBooks() {
        $conn = getDatabaseConnection();
        $sql = "SELECT COUNT(*) as total FROM book WHERE bookStatus = 'available'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $conn->close();
        return $row['total'];
    }

    // Function to get total books
    function getTotalBooks() {
        $conn = getDatabaseConnection();
        $sql = "SELECT COUNT(*) as total FROM book";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $conn->close();
        return $row['total'];
    }

    // Function to get book categories
    function getBookCategories() {
        $conn = getDatabaseConnection();
        $sql = "SELECT bookCategory, COUNT(*) as count FROM book GROUP BY bookCategory";
        $result = $conn->query($sql);

        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }

        $conn->close();
        return $categories;
    }

    // Function to get top rented books for the current month
    function getTopBooksByMonth() {
        $conn = getDatabaseConnection();
        $sql = "SELECT b.bookID, b.bookTitle, b.bookImage, COUNT(r.bookID) as rentals 
                FROM rental r
                JOIN book b ON r.bookID = b.bookID
                WHERE MONTH(r.StartDate) = MONTH(CURDATE()) AND YEAR(r.StartDate) = YEAR(CURDATE())
                GROUP BY r.bookID
                ORDER BY rentals DESC
                LIMIT 3";
        $result = $conn->query($sql);

        $topBooks = [];
        while ($row = $result->fetch_assoc()) {
            $topBooks[] = $row;
        }

        $conn->close();
        return $topBooks;
    }

    $bookCategories = getBookCategories();
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const ctx = document.getElementById('bookCategoriesChart').getContext('2d');
            const categoriesData = <?php echo json_encode($bookCategories); ?>;
            const labels = categoriesData.map(category => category.bookCategory);
            const data = categoriesData.map(category => category.count);

            const backgroundColors = [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(199, 199, 199, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)'
            ];

            const borderColors = [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(199, 199, 199, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)'
            ];

            const bookCategoriesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: backgroundColors,
                        borderColor: borderColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>

    <style>
        .top-books {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .book-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            background-color: #fff;
            border-radius: 5px;
            padding: 10px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.1);
            width: 80%;
            text-decoration: none;
            color: inherit;
        }
        .book-item:hover {
            background-color: #f0f0f0;
        }
        .book-img {
            width: 50px;
            height: 50px;
            margin-right: 15px;
            border-radius: 5px;
        }
        .book-title {
            font-size: 16px;
            font-weight: 500;
            color: #333;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
