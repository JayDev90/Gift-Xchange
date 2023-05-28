<?php
include '../config/db_config.php';

if (!isset($_POST['userId']) || !isset($_POST['giftName'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request.']);
    exit();
}

$userId = $_POST['userId'];
$giftName = $_POST['giftName'];

// Validation
if (!filter_var($userId, FILTER_VALIDATE_INT)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid user id.']);
    exit();
}

if (strlen($giftName) > 255) {
    http_response_code(400);
    echo json_encode(['error' => 'Gift name is too long.']);
    exit();
}

$conn = new mysqli($servername, $username, $password, $dbname);

// If connection fails, send a generic error message
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error.']);
    exit();
}

$sql = "INSERT INTO gifts (userid, name) VALUES (?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error.']);
    exit();
}

$stmt->bind_param("is", $userId, $giftName);

// If execution fails, send a generic error message
if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error.']);
    exit();
}

$giftId = $stmt->insert_id;
echo json_encode(['success' => 'Gift added successfully.', 'giftId' => $giftId]);

$conn->close();
?>
