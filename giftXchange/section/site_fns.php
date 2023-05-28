<?php
include '../config/db_config.php';

function side_menu()
{
    $current_page = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL);

    $str = '<div class="menu-container">';
    $str .= '<ul class="menu">';
    $str .= '<li class="menu-item"><a ' . ($current_page == '/giftXchange/section/user_dashboard.php' ? 'class="active"' : '') . ' href="/giftXchange/section/user_dashboard.php"><i class="fas fa-tachometer-alt"></i> <span>' . SIDE_MENU_MY_DASHBOARD . '</span></a></li>';
    $str .= '<li class="menu-item"><a ' . ($current_page == '/giftXchange/section/add_gift.php' ? 'class="active"' : '') . ' href="/giftXchange/section/add_gift.php"><i class="fas fa-gift"></i> <span>' . SIDE_MENU_CREATE_LIST . '</span></a></li>';
    $str .= '<li class="menu-item"><a ' . ($current_page == '/giftXchange/section/my_gift_list.php' ? 'class="active"' : '') . ' href="/giftXchange/section/my_gift_list.php"><i class="fas fa-list-alt"></i> <span>' . SIDE_MENU_MY_GIFT_LIST . '</span></a></li>';
    $str .= '<li class="menu-item"><a ' . ($current_page == '/giftXchange/section/others_wishlist.php' ? 'class="active"' : '') . ' href="/giftXchange/section/others_wishlist.php"><i class="fas fa-users"></i> <span>' . SIDE_MENU_OTHERS_GIFT_LIST . '</span></a></li>';
    $str .= '<li class="menu-item"><a ' . ($current_page == '/giftXchange/section/reset_password_form.php' ? 'class="active"' : '') . ' href="/giftXchange/section/reset_password_form.php"><i class="fa-solid fa-lock"></i> <span>' . SIDE_MENU_RESET_PASSWORD . '</span></a></li>';
    $str .= '<li class="menu-item"><a ' . ($current_page == '/giftXchange/section/logout.php' ? 'class="active"' : '') . ' href="/giftXchange/section/logout.php"><i class="fas fa-sign-out-alt"></i> <span>' . MSG_LOGOUT . '</span></a></li>';
    $str .= '</ul>';
    $str .= '</div>';

    return $str;
}

function tile_container()
{
    $str = '<div class="tile-container">';
    $str .= '<a href="/giftXchange/section/add_gift.php" class="tile create-list"><i class="fas fa-gift" style="padding: 5px"></i> ' . TILE_MENU_CREATE_LIST . '</a>';
    $str .= '<a href="/giftXchange/section/my_gift_list.php" class="tile my-gift-list"><i class="fas fa-list-alt" style="padding: 5px"></i> ' . TILE_MENU_MY_GIFT_LIST . '</a>';
    $str .= '<a href="/giftXchange/section/others_wishlist.php" class="tile others-gift-list"><i class="fas fa-users" style="padding: 5px"></i> ' . TILE_MENU_OTHERS_GIFT_LIST . '</a>';
    $str .= '<a href="/giftXchange/section/reset_password_form.php" class="tile others-gift-list"><i class="fa-solid fa-lock" style="padding: 5px"></i> ' . TILE_MENU_RESET_PASSWORD . '</a>';
    $str .= '<a href="/giftXchange/section/logout.php" class="tile logout"><i class="fas fa-sign-out-alt" style="padding: 5px"></i> ' . MSG_LOGOUT . '</a>';
    $str .= '</div>';

    return $str;
}

function days_until_xmas() {
    // Get the current date and time
    $current_date = new DateTime();

    // Set the target date to December 25th of the current year
    $target_date = new DateTime(date('Y') . '-12-25');

    // Calculate the difference between the two dates
    $date_diff = date_diff($current_date, $target_date);

    // Get the number of days until December 25th
    $days_until_xmas = $date_diff->format('%a');

    // Determine the message to display based on the number of days
    if ($days_until_xmas == 60) {
        $message = "Only $days_until_xmas Days Until Christmas! Christmas is coming!";
    } elseif ($days_until_xmas == 30) {
        $message = "Only $days_until_xmas Days Until Christmas! Complete your list, check it twice!";
    } elseif ($days_until_xmas <= 10) {
        $message = "$days_until_xmas Days Until Christmas! The Whos in Whoville are getting excited! Are you?";
    } elseif ($days_until_xmas == 1) {
        $message = "Tomorrow is Christmas! It's practically here!";
    } else {
        $message = $days_until_xmas . ' ' . MSG_CHRISTMAS_COUNTDOWN_DEFAULT;
    }

    return $message;
}

function user_dashboard_greeting() {
    include '../config/db_config.php';

    $db = mysqli_connect($servername, $username, $password, $dbname);

    // Start a new session for the user
    session_start();

    // Regenerate the session ID to prevent session fixation attacks
    session_regenerate_id(true);

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
        error_log('Query failed: ' . mysqli_error($db));
        die('An error occurred. Please try again.');
    }

    // Retrieve the user's first name and user ID from the query result
    $row = mysqli_fetch_assoc($result);
    $first_name = $row['first_name'];
    $user_id = $row['userid']; // Store the user ID

    // Save the user ID in the session
    $_SESSION['userId'] = $user_id;

    // Close the database connection
    mysqli_close($db);

    // Return the first name
    return $first_name;
}

function getUserGiftList($userId) {
    global $servername, $username, $password, $dbname;

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM gifts WHERE userid = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $gifts = [];
    while ($row = $result->fetch_assoc()) {
        $gifts[] = $row['name'];
    }

    $stmt->close();
    $conn->close();

    return $gifts;
}

function display_pagination($page_number, $total_pages) {
    echo '<div class="pagination">';

    $search_query = isset($_GET['search']) ? "&search=" . $_GET['search'] : '';

    if ($page_number > 1) {
        echo '<a href="?page=' . ($page_number - 1) . $search_query . '">Previous</a>';
    }

    for ($i = 1; $i <= $total_pages; $i++) {
        $class = $i == $page_number ? 'class="active"' : '';
        echo '<a href="?page=' . $i . $search_query . '" ' . $class . '>' . $i . '</a>';
    }

    if ($page_number < $total_pages) {
        echo '<a href="?page=' . ($page_number + 1) . $search_query . '">Next</a>';
    }

    echo '</div>';
}