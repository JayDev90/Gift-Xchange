<?php
include '../config/db_config.php';
include '../config/constants.php';
include '../section/site_fns.php';

$db = mysqli_connect($servername, $username, $password, $dbname);

// Retrieve the userId from the URL parameter and sanitize it
if (isset($_GET['userId'])) {
    $userId = mysqli_real_escape_string($db, $_GET['userId']);
}

// Query the database to retrieve the first name of the user
$sql = "SELECT first_name FROM users WHERE userid = ?";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId); // Use integer parameter
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if the query is successful
if (!$result) {
    die('Query failed: ' . mysqli_error($db));
}

// Retrieve the first name of the user
$row = mysqli_fetch_assoc($result);
$first_name = $row['first_name'];

// Query the database to retrieve the gifts associated with the user
$sql_gifts = "SELECT g.name, g.status, u.first_name AS user_first_name FROM gifts AS g INNER JOIN users AS u ON g.userid = u.userid WHERE u.userid = ?";
$stmt_gifts = mysqli_prepare($db, $sql_gifts);
mysqli_stmt_bind_param($stmt_gifts, "i", $userId); // Use integer parameter
mysqli_stmt_execute($stmt_gifts);
$result_gifts = mysqli_stmt_get_result($stmt_gifts);

// Check if the query is successful
if (!$result_gifts) {
    die('Query failed: ' . mysqli_error($db));
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Other Users Wishlist</title>
    <link rel="stylesheet" type="text/css" href="/giftXchange/styles/base.css">
    <link rel="stylesheet" type="text/css" href="/giftXchange/styles/others_gifts.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.4.0/css/all.css">
    <script src="/giftXchange/scripts/others_gift_list.js"></script>
</head>
<body>
<div class="background-image"></div>
<div class="container">
    <div class="form-group">
        <h1 style="text-align: center"><?php echo isset($first_name) ? htmlentities($first_name) : ""; ?>'s Gifts</h1>
        <table class="gifts-table">
            <thead>
            <tr>
                <th class="gift" style="font-size: larger"><i class="fa-solid fa-gifts"></i> Gift</th>
                <th class="status" style="font-size: larger; text-align: center;"><i class="fa-solid fa-square-check"></i> Status</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row_gift = mysqli_fetch_assoc($result_gifts)): ?>
            <tr>
                <td style="font-size: large"><?php echo htmlentities($row_gift['name']); ?></td>
                <td style="text-align: center">
                    <?php if ($row_gift['status'] == NULL || $row_gift['status'] == 'Plan to purchase'): ?>
                        <select name="status" class="status-dropdown" onchange="showSaveButton('<?php echo htmlentities($row_gift['name']); ?>'); updateStatus('<?php echo htmlentities($row_gift['name']); ?>', this.value)">
                            <option value="Available" <?php echo $row_gift['status'] == 'Available' ? 'selected' : ''; ?>>Select Option</option>
                            <option value="Plan to purchase" <?php echo $row_gift['status'] == 'Plan to purchase' ? 'selected' : ''; ?>>Plan to purchase</option>
                            <option value="Purchased" <?php echo $row_gift['status'] == 'Purchased' ? 'selected' : ''; ?>>Purchased</option>
                        </select>
                    <?php else: ?>
                        <?php echo htmlentities($row_gift['status']); ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <button type="button" style="margin-top: 15px;" onclick="location.href='../section/user_dashboard.php'">Return to Dashboard</button>
        <button type="button" style="margin-top: 15px;" onclick="location.href='../section/others_wishlist.php'">Return to Everyone's Gifts</button>
        <button id="saveButton" class="save-btn" type="button" onclick="saveUpdatedStatus()">Save</button>
    </div>

</div>
</body>
</html>
