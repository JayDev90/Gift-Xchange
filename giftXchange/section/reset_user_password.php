<?php
include '../config/db_config.php';

// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve passwords from the form
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare a statement to retrieve the current password from the database
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_password = $row['password'];

        // Verify that the old password matches the current password
        if (!password_verify($old_password, $current_password)) {
            echo "<script>alert('Old password is incorrect!'); window.location.href='reset_password_form.php';</script>";
            exit();
        }
    }

    // Check if the new password matches the confirm password
    if ($new_password != $confirm_password) {
        echo "<script>alert('New password and confirm password do not match!'); window.location.href='reset_password_form.php';</script>";
        exit();
    }

    // Before updating the password in the database, hash the new password
    $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

    // Prepare a statement to update the password in the database
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $new_password_hashed, $_SESSION['username']);

    if ($stmt->execute()) {
        echo "<script>alert('Password updated successfully!'); window.location.href='https://localhost/giftXchange/index.php';</script>";
    } else {
        echo "<script>alert('Error updating password: " . $conn->error . "'); window.location.href='reset_password_form.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
