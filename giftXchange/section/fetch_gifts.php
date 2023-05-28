<?php
include '../config/db_config.php';

$userId = $_POST['userId'];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch both name and status from the gifts table
$sql = "SELECT name, status FROM gifts WHERE userid = $userId";
$result = $conn->query($sql);
$gifts = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $gifts[] = $row;
    }
}

echo json_encode($gifts);

$conn->close();
?>
