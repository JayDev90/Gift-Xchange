<?php
include 'C:\xampp\htdocs\giftXchange\config\constants.php';
include '../config/db_config.php';
include 'C:\xampp\htdocs\giftXchange\section\site_fns.php';

session_start();
if (isset($_SESSION['message'])) {
    echo $_SESSION['message'];
    unset($_SESSION['message']); // Clear the message so it's not shown again
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}
// queries the database for user info and login status'
$sql = "SELECT u.username, CONCAT(u.first_name, ' ', u.last_name) AS Name, MAX(s.last_logged_in) AS 'Last Login'
        FROM users u
        LEFT JOIN session s
        ON u.userid = s.userid
        WHERE u.status = 'active'
        GROUP BY u.userid";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <meta name="description" content="Description of the page">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="\giftXchange\styles\admin_page.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<!-- The background image for the page -->
<div class="background"></div>
<div class="main-content">
    <!-- Text in the body of the page -->
    <div class="background-image"></div>
    <div class="container">
        <div class="form-group">
            <h1 style="text-align: center">Admin Dashboard</h1>
        </div>
        <div class="user-list-drop-down">
            <label for="userStatus">User Status:</label>
            <select id="userStatus" name="userStatus">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div id="adminMenu">
            <div class="view-users-header">
                <table>
                    <thead>
                    <tr>
                        <th style="width: 100px;">Username</th>
                        <th style="width: 150px;">Name</th>
                        <th style="width: 150px;">Last Login</th>
                        <th style="width: 50px;" >Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="view-users-body">
                <table>
                    <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) { ?>
                        <tr id="row_<?php echo $row['username'] ?>">
                            <td><?php echo $row['username'] ?></td>
                            <td><?php echo $row['Name'] ?></td>
                            <td><?php echo $row['Last Login'] ?></td>
                            <td><button type="button" id="resetPasswordBtn" onclick="resetPassword('<?php echo $row['username'] ?>')">Reset</button></td>
                            <td><button type="button" class="deleteUserBtn" id="deleteUserBtn_<?php echo $row['username'] ?>" data-username="<?php echo $row['username'] ?>">Delete</button></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
            <div class="button-container">
                <button type="button" id="addUserBtn" style="margin-top: 15px;" onclick="location.href='create_account_form.php'">Add User</button>
                <button type="button" id="logOutBtn" style="margin-top: 15px;" onclick="location.href='logout.php'">Logout</button>
            </div>
    </div>
</div>
<script type="text/javascript" src="\giftXchange\scripts\admin_page.js"></script>
</body>
</html>
