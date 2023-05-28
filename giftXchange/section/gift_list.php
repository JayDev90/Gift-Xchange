<?php
include '../config/db_config.php';

session_start();

// Check if the user is authenticated
if (!isset($_SESSION["authenticated"])) {
    die("You are not authenticated");
}

header("Content-Type: text/plain");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['gifts']) && isset($_POST['userId'])) {
        $gifts = json_decode($_POST['gifts'], true);

        // Check if gifts is an array
        if (!is_array($gifts)) {
            die("Invalid gifts data");
        }

        $userId = $_POST['userId'];

        // Check if userId is an integer
        if (!filter_var($userId, FILTER_VALIDATE_INT)) {
            die("Invalid user id");
        }

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection to the database failed: " . $conn->connect_error);
        }

        $success = true;

        foreach ($gifts as $giftName) {
            // Validate giftName here (e.g., check length, allowed characters, etc.)
            $stmt = $conn->prepare("INSERT INTO gifts (userid, name) VALUES (?, ?)");
            $stmt->bind_param("is", $userId, $giftName);

            if (!$stmt->execute()) {
                $success = false;
                echo "Error executing statement: " . $stmt->error;
                break;
            }
        }

        $stmt->close();
        $conn->close();

        if ($success) {
            echo "success";
        } else {
            echo "Operation failed";
        }
    } else {
        echo "Invalid input data";
    }
} else {
    echo "Invalid request method";
}
?>
