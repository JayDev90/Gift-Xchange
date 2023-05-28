<?php
include '../config/db_config.php';

$db = mysqli_connect($servername, $username, $password, $dbname);

// Start a new session for the user
session_start();
  
if (!isset($_SESSION['username'])) {
  header('Location: /giftXchange/index.php');
  exit();
}

$user = $_SESSION['username'];

// Prepare the query to retrieve the user's first name and user ID
$sql = "SELECT userid, first_name FROM users WHERE username=?";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "s", $user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if the query is successful
if (!$result) {
  die('Query failed: ' . mysqli_error($db));
}

// Retrieve the user's first name and user ID from the query result
$row = mysqli_fetch_assoc($result);
$first_name = $row['first_name'];
$user_id = $row['userid']; // Store the user ID

// Save the user ID in the session
$_SESSION['userId'] = $user_id;

// Close the database connection
mysqli_close($db);
?>
