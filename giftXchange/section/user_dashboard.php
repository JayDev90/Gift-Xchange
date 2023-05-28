<?php

include '../section/site_fns.php';
include '../config/constants.php';
?>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard</title>
    <meta name="description" content="Description of the page">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.4.0/css/all.css">
    <link rel="stylesheet" type="text/css" href="../styles/user_dashboard.css">
  </head>
  <body>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <!-- The background image for the page -->
  <div class="background"></div>
  <div class="main-content">
    <!-- Text in the body of the page -->
    <div class="greeting">
      <h1 style="font-size: xxx-large">Hi <?php echo htmlspecialchars(user_dashboard_greeting(), ENT_QUOTES, 'UTF-8'); ?>!</h1>
      <p style="font-size: x-large">What would you like to do?</p>
    </div>
    <?php echo tile_container(); ?>
    <div class="christmas-countdown">
      <p><?php echo days_until_xmas() ?></p>
    </div>
  </div>
  </body>
</html>
