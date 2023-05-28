<?php
include '../config/db_config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if (isset($_POST['status'])) {
    $status = $_POST['status'];

    $sql = "SELECT u.username, CONCAT(u.first_name, ' ', u.last_name) AS Name, MAX(s.last_logged_in) AS 'Last Login'
            FROM users u
            LEFT JOIN session s
            ON u.userid = s.userid
            WHERE u.status = ?
            GROUP BY u.userid";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo '<tr id="row_' . $row['username'] . '">';
        echo '<td>' . $row['username'] . '</td>';
        echo '<td>' . $row['Name'] . '</td>';
        echo '<td>' . $row['Last Login'] . '</td>';
        echo '<td><button type="button" id="resetPasswordBtn" onclick="resetPassword(\'' . $row['username'] . '\')">Reset</button></td>';
        echo '<td><button type="button" id="deleteUserBtn_' . $row['username'] . '" data-username="' . $row['username'] . '">Delete</button></td>';
        echo '</tr>';
    }
} else {
    echo 'No status provided.';
}
?>

