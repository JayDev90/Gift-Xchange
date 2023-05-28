<?php
include 'C:\xampp\htdocs\giftXchange\config\constants.php';
include '../config/db_config.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_to_reset = $_POST['username'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password != $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
        exit();
    }

    $sql = "UPDATE users SET password = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", password_hash($new_password, PASSWORD_DEFAULT), $user_to_reset);
    $stmt->execute();

    if ($stmt->affected_rows == 1) {
        $_SESSION['message'] = "<div id='passwordMessage' style='text-align: center; margin-top: 40px; font-weight: bold; color: white; font-size: large'>Password updated successfully!</div>";
        header('Location: ../section/admin_page.php');
        exit();
    } else {
        echo "Error updating password!";
    }

    $stmt->close();
    $conn->close();
}
?>
