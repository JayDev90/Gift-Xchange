<?php
include 'C:\xampp\htdocs\giftXchange\config\constants.php';
include '../config/db_config.php';
include 'C:\xampp\htdocs\giftXchange\section\site_fns.php';

// TODO: enable session_set_cookie_params function when ready for production
session_set_cookie_params([
    'secure' => true,     // this means the browser should only send the cookie over https
    'httponly' => true,   // this means the cookie is made accessible only through the HTTP protocol and not client-side scripts
    'samesite' => 'Strict', // this means the browser will only send cookies if the request originated from the website that set the cookie; if the request originated from a different URL than the URL of the current location, none of the cookies tagged with the Samesite=Strict attribute will be included
]);

session_start();

session_regenerate_id(true);

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$users_per_page = 5;
$page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$currentUsername = $_SESSION['username'];
$total_users_query = "SELECT COUNT(*) as total FROM users WHERE username <> ? AND username <> 'Admin' AND status = 'active'";
$stmt = $conn->prepare($total_users_query);
$stmt->bind_param("s", $currentUsername);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_users = $row['total'];

$total_pages = ceil($total_users / $users_per_page);

$offset = ($page_number - 1) * $users_per_page;

$searchTermSet = isset($_GET['search']) && trim($_GET['search']) !== "" ? true : false;

$search_query = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : '%';
$sql = "SELECT userid, first_name, last_name FROM users WHERE username <> ? AND username <> 'Admin' AND status = 'active' AND CONCAT(first_name, ' ', last_name) LIKE ? ORDER BY first_name LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $currentUsername, $search_query, $users_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Others Wishlist</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="\giftXchange\styles\others_gifts_list.css">
    <link rel="stylesheet" type="text/css" href="/giftXchange/styles/pagination.css">
    <link rel="stylesheet" type="text/css" href="\giftXchange\styles\base.css">
    <link rel="stylesheet" type="text/css" href="\giftXchange\styles\others_giftlist_preview.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.4.0/css/all.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/giftXchange/scripts/others_wishlist.js"></script>
</head>
<body>
<div class="background-image"></div>
<div class="background"></div>
<?php echo side_menu() ?>

<div class="container">
    <div class="form-group">
        <h1 style="text-align: center"><?php echo TITLE_OTHERS_WISHLIST ?></h1>
        <div class="search-bar">
            <a href="/giftXchange/section/others_wishlist.php" class="view-all-btn" id="viewAllButton">View All</a>
            <form action="" method="get">
                <input type="text" id="searchInput" name="search" placeholder="Search Recipients">
                <input type="submit" value="Search">
            </form>
        </div>
    </div>
    <div id="userList">
        <?php
        if ($result->num_rows > 0) {
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            // Define a custom comparison function that compares the "first_name" column of two rows
            function compareRows($a, $b) {
                return strcmp($a["first_name"], $b["first_name"]);
            }
            // Sort the array of rows using the custom comparison function
            usort($rows, "compareRows");
            // Output the sorted rows
            foreach ($rows as $row) {
                echo '<div class="user-container">';
                echo '<div class="user-item" data-userid="' . $row["userid"] . '">';
                echo '<span id="user-name-' . $row["userid"] . '">' . htmlspecialchars($row["first_name"]) . " " . htmlspecialchars($row["last_name"]) . '</span>';
                echo '</div>';
                echo '<div class="gift-list" style="display:none;"></div>';
                echo '<button id="gift-list-button-' . $row["userid"] . '" class="gift-list-button" type="button" style="display:none; margin-bottom: 10px; margin-left: 80px;"><i class="fa-solid fa-arrow-right" style="padding: 2px"></i>Go to ' . $row["first_name"] . '\'s list</button>';
                echo '</div>';
            }
        } else {
            echo "0 users found.";
        }
        ?>
    </div>
    <?php display_pagination($page_number, $total_pages); ?>
</div>
<div style="text-align: center;">
    <h2 style="color: white; font-family: 'Open Sans', sans-serif;"> Santa's bag is getting full!</h2>
</div>

<?php
$conn->close();
?>
<script>
    $(document).ready(function(){
        $('#searchInput').on('input', function() {
            var searchValue = $(this).val();
            if (searchValue === '') {
                $('#viewAllButton').css('background-color', '#008F5A');
                $('#viewAllButton').hover(function() {
                    $(this).css('background-color', '#008F5A'); // this is the same color when there's no search query
                }, function() {
                    $(this).css('background-color', '#008F5A'); // this is the same color when there's no search query
                });
            } else if (searchValue !== '') {
                $('#viewAllButton').css('background-color', '#FF5057');
                $('#viewAllButton').hover(function() {
                    $(this).css('background-color', '#008F5A'); // hover color
                }, function() {
                    $(this).css('background-color', '#FF5057'); // non-hover color
                });
            }
        });

        <?php if($searchTermSet) : ?>
        $('#viewAllButton').css('background-color', '#FF5057');
        $('#viewAllButton').hover(function() {
            $(this).css('background-color', '#008F5A'); // hover color
        }, function() {
            $(this).css('background-color', '#FF5057'); // non-hover color
        });
        <?php endif; ?>
    });
</script>
</body>
</html>
