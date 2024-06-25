<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wormdb";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$username = sanitize_input($_POST["username"]);
$password = sanitize_input($_POST["password"]);

$sql = "SELECT password FROM students WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        header("Location: login.html?status=success");
    } else {
        header("Location: login.html?status=wrongpass");
    }
} else {
    header("Location: login.html?status=nouser");
}

$stmt->close();
$conn->close();
?>
