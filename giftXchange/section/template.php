<?php
include 'C:\xampp\htdocs\giftXchange\config\constants.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template</title>
    <meta name="description" content="Description of the page">
    <link rel="stylesheet" type="text/css" href="\giftXchange\styles\base.css">
    <script src="script.js" defer></script>
</head>
<body>
<div class="background-image"></div>
<!-- Side Bar Menu. Must change class="active" -->
<ul>
    <li><a class="active" href="\giftXchange\section\userpage.php"><?php echo SIDE_MENU_MY_DASHBOARD ?></a></li>
    <li><a href="\giftXchange\section\createWishlist.php"><?php echo SIDE_MENU_CREATE_LIST ?></a></li>
    <li><a href="\giftXchange\section\my_wishlist.php"><?php echo SIDE_MENU_MY_GIFT_LIST ?></a></li>
    <li><a href="\giftXchange\section\others_wishlist.php"><?php echo SIDE_MENU_OTHERS_GIFT_LIST ?></a></li>
    <li><a href="\giftXchange\section\logout.php"><?php echo MSG_LOGOUT ?></a></li>
</ul>
<div class="container">
    <div class="content-container">
        <h1>test</h1>
        <!-- Button type="button" default for all buttons throughout the web app -->
        <button type="button">test</button>
    </div>
</div>
</body>
</html>