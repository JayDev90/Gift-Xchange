<?php
include '../config/db_config.php';

if (!isset($_POST['giftId']) || !ctype_digit($_POST['giftId'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request.']);
    exit();
}

$giftId = $_POST['giftId'];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error.']);
    exit();
}

$sql = "DELETE FROM gifts WHERE giftid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $giftId);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error.']);
    exit();
}

echo json_encode(['success' => 'Gift deleted successfully.']);

$conn->close();
?>
