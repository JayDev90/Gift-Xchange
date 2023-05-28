<?php
include '../config/db_config.php';
session_start();

if (!isset($_POST['userId']) || !isset($_POST['updatedGifts']) || !isset($_POST['newGifts'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request.']);
    exit();
}

$userId = $_POST['userId'];
$updatedGifts = $_POST['updatedGifts'];
$newGifts = $_POST['newGifts'];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Update existing gifts
foreach ($updatedGifts as $giftId => $giftName) {
    $sql = "UPDATE gifts SET name = ? WHERE giftid = ? AND userid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $giftName, $giftId, $userId);

    if (!$stmt->execute()) {
        http_response_code(500);
        echo json_encode(['error' => 'Error updating gift: ' . $stmt->error]);
        exit();
    }
}

// Add new gifts
foreach ($newGifts as $giftName) {
    if (empty($giftName)) {
        continue; // Skip to next iteration if gift name is empty
    }

    // Check if the giftName is a URL
    if (filter_var($giftName, FILTER_VALIDATE_URL)) {
        // It's a URL, sanitize it

        // Only allow http and https protocols
        if (!preg_match('/^https?:\/\//i', $giftName)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid URL protocol. Only http and https are allowed.']);
            exit();
        }

        // Block known malicious strings
        $maliciousStrings = ['javascript:'];
        foreach ($maliciousStrings as $maliciousString) {
            if (strpos($giftName, $maliciousString) !== false) {
                http_response_code(400);
                echo json_encode(['error' => 'The URL contains a malicious string.']);
                exit();
            }
        }

        // Add more checks here as necessary
    }

    $sql = "INSERT INTO gifts (userid, name) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $userId, $giftName);

    if (!$stmt->execute()) {
        http_response_code(500);
        echo json_encode(['error' => 'Error adding gift: ' . $stmt->error]);
        exit();
    }
}


echo json_encode(['success' => 'Gifts updated successfully.']);

$conn->close();
?>
