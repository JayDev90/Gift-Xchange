<?php
// Connect to your MySQL database
include '../config/db_config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all usernames from the users table
$sql = "SELECT username FROM users";
$result = $conn->query($sql);
$usernames = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        array_push($usernames, $row['username']);
    }
}
header('Content-Type: application/json; charset=utf-8');
// Prevent MIME types confusion attacks
header('X-Content-Type-Options: nosniff');
// Clickjacking protection
header('X-Frame-Options: SAMEORIGIN');

echo json_encode($usernames);

$conn->close();
?>
