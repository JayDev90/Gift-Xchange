<?php
include '../config/db_config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if (isset($_POST['username'])) {
$username = $_POST['username'];

$sql = "UPDATE users SET status = 'inactive' WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);

if ($stmt->execute()) {
echo json_encode(['status' => 'success', 'message' => 'User deleted successfully.']);
} else {
echo json_encode(['status' => 'error', 'message' => 'Error deleting user.']);
}
} else {
echo json_encode(['status' => 'error', 'message' => 'No username provided.']);
}
