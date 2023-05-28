<?php
// Including the configuration file where we have defined our constants.
include 'config/constants.php';

// Starting a new session or resuming the existing one. This is used to manage and maintain data (like user login details) across different pages.
session_start();
?>

<!DOCTYPE html>
<!-- The DOCTYPE declaration is used to inform a website visitor's browser about the version of HTML the page is written in. -->

<html lang="en">
<!-- "lang" attribute describes the language of the document's content -->

<head>
    <!-- The head element contains metadata about the HTML document (data about data). It's where we place our CSS links, our meta elements, and our JavaScript links. -->

    <meta charset="UTF-8">
    <!-- Setting the document's character encoding to UTF-8 -->

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Making the design responsive to mobile or any other devices -->

    <title>Gift Xchange</title>
    <!-- The title of the webpage -->

    <!-- Linking external Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <!-- Linking external CSS files for styling the webpage -->
    <link rel="stylesheet" type="text/css" href="/giftXchange/styles/index.css">
    <link rel="stylesheet" type="text/css" href="\giftXchange\styles\base.css">
</head>
<body>
<!-- The content that will be visible to users on the webpage -->

<div class="login-container">
    <!-- This div contains all elements related to the login functionality -->

    <div class="background-image"></div>
    <!-- Placeholder for a background image -->

    <div class="background-text"><?php echo WEBSITE_TITLE ?></div>
    <!-- Displaying the website title -->

    <div class="slogan"><h3>Like Santa, make a list and check it twice!</h3></div>
    <!-- A catchphrase or slogan for the website -->

    <div class="description">A Christmas gift exchange platform where you can create gift lists, view family and friends' lists and make the generous season of gift-giving more organized and enjoyable!</div>
    <!-- Description of the website -->

    <div class="login-card">
        <!-- The login form is encapsulated in this div -->

        <h2 style="text-align:center; margin-top:20px;"><?php echo MSG_LOGIN ?></h2>
        <!-- Login header -->

        <!-- If there is an error message, it will be displayed here -->
        <?php if (isset($_GET['error'])): ?>
            <div style="color:red; margin-bottom:20px;"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <!-- Login form starts here. On submission, it sends a POST request to 'login.php' -->
        <form method="POST" action="/giftXchange/section/login.php">
            <!-- Input field for username -->
            <input type="text" name="username" placeholder="Username" style="width:75%; padding:10px; margin-bottom:20px; border-radius:5px; border:none;">

            <!-- Input field for password -->
            <input type="password" name="password" placeholder="Password" style="width:75%; padding:10px; margin-bottom:20px; border-radius:5px; border:none;">

            <!-- Submit button for the login form -->
            <button type="submit" style="width:50%; padding:10px; margin-top:20px;"><?php echo MSG_LOGIN ?></button>
        </form>
        <!-- Login form ends here -->

        <!-- Displaying a message to create an account if the user doesn't have one -->
        <p style="text-align:center; margin-top:20px;"><?php echo MSG_DONT_HAVE_AN_ACCOUNT ?><a href="/giftXchange/section/create_account_form.php" style="color: #008F5A;"><?php echo MSG_SIGN_UP ?></a></p>
    </div>
    <!-- End of the login form div -->
</div>
<!-- End of the login container -->

<!-- The footer of the webpage -->
<footer>
    <!-- Displaying the copyright notice -->
    <p>&copy; <?php echo date('Y'); ?> <?php echo MSG_AUTHOR . ', ' . MSG_AUTHOR_OCCUPATION . ', ' . MSG_RIGHTS_RESERVED; ?></p>
</footer>

</body>
</html>
