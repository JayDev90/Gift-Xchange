<?php
session_start();
include '../config/db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if (empty($user) || empty($pass)) {
        header('Location: https://localhost/giftXchange/index.php?error=' . urlencode('Please enter username and password.'));
        exit();
    } else {
        $db = new mysqli($servername, $username, $password, $dbname);

        if ($db->connect_error) {
            // Custom error logging here
            exit();
        }

        $stmt = $db->prepare("SELECT * FROM users WHERE username=?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            // Custom error logging here
            exit();
        }

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];
            if (password_verify($pass, $hashed_password)) {
                $_SESSION['username'] = $user;
                $_SESSION["authenticated"] = true;

                $userid = $row['userid'];
                $now = date("Y-m-d H:i:s");
                $stmt = $db->prepare("INSERT INTO session (userid, last_logged_in, currently_logged_in) 
                  VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE last_logged_in=?, currently_logged_in=1");
                $stmt->bind_param("sss", $userid, $now, $now);
                $stmt->execute();

                if ($user == 'Admin') {
                    header('Location: https://localhost/giftXchange/section/admin_page.php');
                    exit();
                } else {
                    header('Location: https://localhost/giftXchange/section/user_dashboard.php');
                    exit();
                }
            }else {
        // Display an error message if the username or password is incorrect
                header('Location: https://localhost/giftXchange/index.php?error=' . urlencode('Incorrect password.'));
                exit();
            }
            $db->close();
        }
    }
}
header('Location: https://localhost/giftXchange/index.php');
exit();
?>