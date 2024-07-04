<?php
include 'db.php'; // Include your database connection
$title = "Customer Data"; // Title of the page
include 'StaffHeader.php'; // Include header HTML

// Define the number of results per page
$results_per_page = 25;

// Find out the number of results stored in database
$sql = "SELECT COUNT(userid) AS total FROM customer INNER JOIN system_users ON customer.custid = system_users.userid";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$number_of_results = $row['total'];

// Determine number of total pages available
$number_of_pages = ceil($number_of_results / $results_per_page);

// Determine which page number visitor is currently on
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int) $_GET['page'] : 1;
$current_page = $page; // Set the current page

// Determine the SQL LIMIT starting number for the results on the displaying page
$this_page_first_result = ($page - 1) * $results_per_page;

// Fetch customer data with limits for pagination
$query = "SELECT system_users.userid, system_users.username, system_users.fullname, LOWER(customer.status) AS status FROM customer INNER JOIN system_users ON customer.custid = system_users.userid LIMIT $this_page_first_result, $results_per_page";
$result = $conn->query($query);

function getStatusClass($status) {
    switch (strtolower($status)) {
        case 'suspend':
            return 'bg-warning';  // Yellow for suspend
        case 'inactive':
            return 'bg-danger';   // Red for inactive
        default:
            return 'bg-secondary'; // Grey for any other status
    }
}
?>
    <style>
        .table {
            width: 100%;
            margin-bottom: 20px;
            background-color: #fff;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .table th, .table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
        }
        .table th {
            background-color: #f8f9fa;
        }
        .badge {
            padding: 0.5em 0.75em;
            border-radius: 0.7em;
        }
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            justify-content: center;
        }
        .pagination li {
            margin: 0 2px;
        }
        .pagination a {
            padding: 8px 12px;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            border: 1px solid transparent;
            transition: background-color 0.3s;
        }
        .pagination a:hover, .pagination a.active {
            background-color: #0056b3;
        }
        .btn-edit, .btn-delete {
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .btn-edit {
            background-color: #17a2b8;
            color: white;
        }
        .btn-edit:hover {
            background-color: #138496;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Customer Details</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["userid"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["fullname"]) . "</td>";
                        echo '<td><span class="badge ' . getStatusClass($row["status"]) . '">' . htmlspecialchars(ucfirst($row["status"])) . '</span></td>';
                        echo '<td>
                                <a class="btn-edit" href="CustomerUpdate.php?id=' . htmlspecialchars($row["userid"]) . '">Update</a>
                                <a class="btn-delete" href="CustomerDelete.php?id=' . htmlspecialchars($row["userid"]) . '">Delete</a>
                              </td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No customer data found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <nav>
            <ul class="pagination">
                <?php
                for ($page = 1; $page <= $number_of_pages; $page++) {
                    $class = ($page == $current_page) ? 'active' : '';
                    echo '<li><a class="page-link ' . $class . '" href="CustomerRead.php?page=' . $page . '">' . $page . '</a></li>';
                }
                if ($current_page < $number_of_pages) {
                    echo '<li><a class="page-link" href="CustomerRead.php?page=' . ($current_page + 1) . '">&gt;&gt;</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div>
</body>
<?php
$conn->close();
?>
