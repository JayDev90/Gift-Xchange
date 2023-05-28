<?php

include '../config/db_config.php';
include 'user_greeting.php';
include 'C:\xampp\htdocs\giftXchange\config\constants.php';
include 'C:\xampp\htdocs\giftXchange\section\site_fns.php';

// Redirect to index.php if the user is not logged in
if (!isset($_SESSION['userId'])) {
    header("Location: ../index.php");
    exit();
}

$userId = $_SESSION['userId'];
$gifts_per_page = 5; // We want to display 5 gifts per page
$page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get the current page number

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get total number of gifts for this user
$total_gifts_query = "SELECT COUNT(*) as total FROM gifts WHERE userid = ?";
$stmt = $conn->prepare($total_gifts_query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_gifts = $row['total'];

$total_pages = ceil($total_gifts / $gifts_per_page); // Calculate total pages

// Prepare the SQL statement using parameterized queries
$offset = ($page_number - 1) * $gifts_per_page; // Calculate the offset

$sql = "SELECT * FROM gifts WHERE userid = ? LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $userId, $gifts_per_page, $offset); // Bind parameters
$stmt->execute();
$result = $stmt->get_result();

$gifts = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $gifts[] = array(
            'id' => $row['giftid'],
            'name' => $row['name']
        );
    }
}

$stmt->close(); // Close the prepared statement

$conn->close();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Wishlist</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/giftXchange/styles/my_gift_list.css">
    <link rel="stylesheet" type="text/css" href="/giftXchange/styles/pagination.css">
    <link rel="stylesheet" type="text/css" href="/giftXchange/styles/base.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.4.0/css/all.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body data-user-id="<?php echo htmlspecialchars($userId); ?>">
<div class="background-image"></div>
<?php echo side_menu(); ?>

<div class="container">
    <div class="form-group">
        <h1 style="text-align: center"><?php echo htmlspecialchars($first_name); ?>'s Gift List</h1>
    </div>
    <div id="giftList">
        <?php
        if (empty($gifts)) {
            echo "<p style='font-size: larger; text-align: center'>You do not have a gift list.</p>";
            $editButtonText = "Return to Dashboard";
            $editButtonId = "returnToDashboard";
        } else {
            $editButtonText = "Edit";
            $editButtonId = "editGifts";
            foreach ($gifts as $gift) : ?>
                <div class="gift-item" data-gift-id="<?php echo htmlspecialchars($gift['id']); ?>">
                        <span>
                            <?php if (strpos($gift['name'], 'https://') === 0) : ?>
                                <a href="<?php echo htmlspecialchars($gift['name']); ?>" target="_blank"><?php echo htmlspecialchars($gift['name']); ?></a>
                            <?php else : ?>
                                <?php echo htmlspecialchars($gift['name']); ?>
                            <?php endif; ?>
                        </span>
                    <button class="delete-btn"><i class="fa-solid fa-trash" style="padding: 5px"></i>Delete</button>
                </div>
            <?php
            endforeach;
        } ?>
    </div>
    <div class="form-group">
        <?php if (empty($gifts)) : ?>
            <div class="button-container">
                <button type="button" id="createGiftList"><i class="fa-solid fa-plus"></i> Create Gift List</button>
                <button type="button" id="<?php echo htmlspecialchars($editButtonId); ?>"><?php echo htmlspecialchars($editButtonText); ?></button>
            </div>
        <?php else : ?>
            <button type="button" id="<?php echo htmlspecialchars($editButtonId); ?>"><?php echo htmlspecialchars($editButtonText); ?></button>
        <?php endif; ?>
        <button type="button" id="addGift" style="display: none;"><i class="fa-solid fa-plus"></i> Add Gift</button>
        <button type="button" id="saveGifts" style="display: none;"><i class="fa-solid fa-floppy-disk"></i> Save</button>
    </div>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modalMessage"></p>
        </div>
    </div>
    <?php display_pagination($page_number, $total_pages); ?>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="\giftXchange\scripts\my_gift_list.js"></script>

</body>
</html>
