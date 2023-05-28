<?php
include '../config/db_config.php';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$first_name = isset($_POST["first_name"]) ? $_POST["first_name"] : "";
$last_name = isset($_POST["last_name"]) ? $_POST["last_name"] : "";
$email = isset($_POST["email"]) ? $_POST["email"] : "";
$username = isset($_POST["username"]) ? $_POST["username"] : "";
$password = isset($_POST["password"]) ? $_POST["password"] : "";
$confirm_password = isset($_POST["confirm_password"]) ? $_POST["confirm_password"] : "";

$first_name = htmlspecialchars(strip_tags(trim($first_name)));
$last_name = htmlspecialchars(strip_tags(trim($last_name)));
$email = htmlspecialchars(strip_tags(trim($email)));
$username = htmlspecialchars(strip_tags(trim($username)));
$password = htmlspecialchars(strip_tags(trim($password)));
$confirm_password = htmlspecialchars(strip_tags(trim($confirm_password)));

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Error: Username already exists. Please choose a different username.";
} else {
    if ($password != $confirm_password) {
        echo "Error: Passwords do not match.";
    } else if (empty($first_name) || empty($last_name) || empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        echo "Error: Please fill in all required fields.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, username, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $first_name, $last_name, $email, $username, $hashed_password);
        if ($stmt->execute()) {
            header("Location: user_dashboard.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

$conn->close();
?>
