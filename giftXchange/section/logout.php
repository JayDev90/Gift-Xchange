<?php
include '../config/db_config.php';
// Start the session
session_start();

// Update the session table to mark the user as logged out
if (isset($_SESSION['username'])) {
  // Include the database configuration file


  // Connect to the MySQL database
  $db = mysqli_connect($servername, $username, $password, $dbname);

  // Check if the connection is successful
  if (!$db) {
    die('Connection failed: ' . mysqli_connect_error());
  }

  // Get the user ID from the session
  $username = $_SESSION['username'];
  $stmt = $db->prepare("SELECT userid FROM users WHERE username=?");
  $stmt->bind_param("s", $username); // "s" means the database expects a string
  $stmt->execute();
  $result = $stmt->get_result();
  $userid = $result->fetch_assoc()['userid'];
  $stmt->close();

  // Update the session record for the current user
  $now = date("Y-m-d H:i:s");
  $stmt = $db->prepare("UPDATE session SET currently_logged_in=0 WHERE userid=? AND currently_logged_in=1");
  $stmt->bind_param("i", $userid); // "i" means the database expects an integer
  $stmt->execute();
  $stmt->close();

  // Close the database connection
  $db->close();
}

// Clear the session data
unset($_SESSION["authenticated"]);
session_unset();
session_destroy();

// Redirect the user to the index.php page
header('Location: /giftXchange/index.php');
exit();
?>
