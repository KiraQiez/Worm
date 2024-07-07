<?php
$title = "Staff Report";
include 'StaffHeader.php';
include 'db.php'; // Include your database connection script

// Database functions
function getDatabaseConnection()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "wormdb";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function getTotalBookRentals()
{
    $conn = getDatabaseConnection();
    $sql = "SELECT COUNT(*) as total FROM rental";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $conn->close();
    return $row['total'];
}

function getTodayBookRentals()
{
    $conn = getDatabaseConnection();
    $sql = "SELECT COUNT(*) as total FROM rental WHERE DATE(StartDate) = CURDATE()";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $conn->close();
    return $row['total'];
}

function getThisMonthBookRentals()
{
    $conn = getDatabaseConnection();
    $sql = "SELECT COUNT(*) as total FROM rental WHERE MONTH(StartDate) = MONTH(CURDATE()) AND YEAR(StartDate) = YEAR(CURDATE())";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $conn->close();
    return $row['total'];
}

function getAvailableBooks()
{
    $conn = getDatabaseConnection();
    $sql = "SELECT COUNT(*) as total FROM book WHERE bookStatus = 'available'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $conn->close();
    return $row['total'];
}

function getTotalBooks()
{
    $conn = getDatabaseConnection();
    $sql = "SELECT COUNT(*) as total FROM book";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $conn->close();
    return $row['total'];
}

function getTotalCustomers()
{
    $conn = getDatabaseConnection();
    $sql = "SELECT COUNT(*) as total FROM customer";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $conn->close();
    return $row['total'];
}

function getTotalStaff()
{
    $conn = getDatabaseConnection();
    $sql = "SELECT COUNT(*) as total FROM staff";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $conn->close();
    return $row['total'];
}

function getBookCategories()
{
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

function getTopBooksByMonth()
{
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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Report</title>
    <link rel="stylesheet" href="path/to/your/stylesheet.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="grid-container">
        <!-- Book Categories -->
        <div class="card card-large">
            <div class="card-body">
                <h5 class="card-title">Book Categories</h5>
                <canvas id="bookCategoriesChart"></canvas>
            </div>
        </div>

        <!-- Top 3 Book Rentals This Month -->
        <div class="card card-medium">
            <div class="card-body">
                <h5 class="card-title">Top 3 Book Rentals This Month</h5>
                <div id="topBooks" class="top-books">
                    <?php
                    $topBooks = getTopBooksByMonth();
                    foreach ($topBooks as $book) {
                        echo '<a href="StaffBookDetails.php?bookID=' . htmlspecialchars($book['bookID']) . '" class="book-item">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($book['bookImage']) . '" alt="' . htmlspecialchars($book['bookTitle']) . '" class="book-img">';
                        echo '<div class="book-title">' . htmlspecialchars($book['bookTitle']) . '</div>';
                        echo '</a>';
                    }
                    ?>
                </div>
            </div>

        </div>

        <!-- Total Book Rental -->
        <div class="card card-small">
            <div class="card-body">
                <div class="card-icon"><i class="fas fa-book-reader"></i></div>
                <h5 class="card-title">Rented Book</h5>
                <h2 class="card-text"><?php echo getTotalBookRentals(); ?></h2>
                <p>Today - <?php echo getTodayBookRentals(); ?> | This month - <?php echo getThisMonthBookRentals(); ?></p>
            </div>
        </div>

        <!-- Book Available -->
        <div class="card card-small">
            <div class="card-body">
                <div class="card-icon"><i class="fas fa-book-open"></i></div>
                <h5 class="card-title">Book Available</h5>
                <h2 class="card-text"><?php echo getAvailableBooks(); ?></h2>
            </div>
        </div>

        <!-- Book Total -->
        <div class="card card-small">
            <div class="card-body">
                <div class="card-icon"><i class="fas fa-book-medical"></i></div>
                <h5 class="card-title">Total Book</h5>
                <h2 class="card-text"><?php echo getTotalBooks(); ?></h2>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="card card-small">
            <div class="card-body">
                <div class="card-icon"><i class="fas fa-users"></i></div>
                <h5 class="card-title">Total Customer</h5>
                <h2 class="card-text"><?php echo getTotalCustomers(); ?></h2>
            </div>
        </div>

        <!-- Total Staff -->
        <div class="card card-small">
            <div class="card-body">
                <div class="card-icon"><i class="fas fa-address-card"></i></div>
                <h5 class="card-title">Total Staff</h5>
                <h2 class="card-text"><?php echo getTotalStaff(); ?></h2>
            </div>
        </div>
    </div>
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
                        label: 'Book Categories',
                        data: data,
                        backgroundColor: backgroundColors,
                        borderColor: borderColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>