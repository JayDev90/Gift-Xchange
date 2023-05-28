<?php
include '../config/db_config.php';

$db = mysqli_connect($servername, $username, $password, $dbname);

if (isset($_POST['giftName']) && isset($_POST['status'])) {
    $giftName = $_POST['giftName'];
    $status = $_POST['status'];

    // Prepare the SQL statement with placeholders
    $sql = "UPDATE gifts SET status = ? WHERE name = ?";
    $stmt = mysqli_prepare($db, $sql);

    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, "ss", $status, $giftName);

    // Execute the prepared statement
    mysqli_stmt_execute($stmt);

    // Check the affected rows
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "Status updated successfully";
    } else {
        echo "Error updating status: " . mysqli_error($db);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}

// Close the database connection
mysqli_close($db);
?>
