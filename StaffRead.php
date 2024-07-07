<?php
include 'db.php'; // Include your database connection
$title = "Staff Data"; // Title of the page
include 'StaffHeader.php'; // Include header HTML

// Define the number of results per page
$results_per_page = 10;

// Find out the number of results stored in the database
$sql = "SELECT COUNT(userid) AS total FROM staff INNER JOIN system_users ON staff.staffid = system_users.userid";
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

// Fetch staff data with limits for pagination
$query = "SELECT system_users.userid, system_users.username, system_users.fullname, staff.stafftype FROM staff INNER JOIN system_users ON staff.staffid = system_users.userid LIMIT $this_page_first_result, $results_per_page";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<body>
    <div class="container mt-5">
        <h2>Staff Details</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Staff Type</th>
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
                        echo "<td>" . htmlspecialchars($row["stafftype"]) . "</td>";
                        echo '<td>
                                <a class="btn-edit" href="StaffUpdate.php?id=' . htmlspecialchars($row["userid"]) . '">Update</a>
                                <a class="btn-delete" href="StaffDelete.php?id=' . htmlspecialchars($row["userid"]) . '">Delete</a>
                              </td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No staff data found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <nav>
            <ul class="pagination">
                <?php
                for ($page = 1; $page <= $number_of_pages; $page++) {
                    $class = ($page == $current_page) ? 'active' : '';
                    echo '<li><a class="page-link ' . $class . '" href="StaffRead.php?page=' . $page . '">' . $page . '</a></li>';
                }
                if ($current_page < $number_of_pages) {
                    echo '<li><a class="page-link" href="StaffRead.php?page=' . ($current_page + 1) . '">&gt;&gt;</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div>
</body>
</html>
<?php
$conn->close();
?>
