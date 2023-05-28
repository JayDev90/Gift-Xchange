<?php
include '../section/user_greeting.php';
include '../config/constants.php';
include '../section/site_fns.php';


$userGiftList = getUserGiftList(htmlspecialchars($_SESSION['userId'], ENT_QUOTES, 'UTF-8'));
$first_name = htmlspecialchars($first_name, ENT_QUOTES, 'UTF-8');

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>My Gift List</title>
  <!-- References the wishlistForm.css -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="\giftXchange\styles\add_gift_form.css">
  <link rel="stylesheet" type="text/css" href="\giftXchange\styles\base.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.4.0/css/all.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script type="text/javascript" src="\giftXchange\scripts\save_wishlist.js"></script>
</head>
<body>
  <!-- The background image for the page -->
  <div class="background-image"></div>
  <?php echo side_menu() ?>

  <!-- Container for the create wishlist form -->
  <div class="container">
    <form action="#">
      <!-- Placeholder text for the wishlist name field -->
      <div class="form-group">
        <?php if (empty($userGiftList)): ?>
          <h1>Hi <?php echo $first_name; ?>, create your gift list here!</h1>
        <div class="button-container">
          <button type="button" id="createGiftListBtn"><i class="fa-solid fa-plus"></i> Create Gift List</button>
        </div>
        <?php else: ?>
          <h1>You already have a gift list!</h1>
          <button type="button" id="goToGiftListBtn" style="font-size: large">
            <i class="fas fa-list-alt" style="margin-right: 5px;"></i>Go to List</button>
        <?php endif; ?>
      </div>
      <!-- Placeholder text for the enter gift field -->
      <div id="giftList"></div>
      <div id="giftListContainer" style="margin-top: 10px"></div>
      <button type="button" id="saveGiftListBtn" style="display:none;"><i class="fa-solid fa-floppy-disk"></i> Save</button>
      <button type="button" id="resetWishlist"><i class="fa-solid fa-rotate-left"></i> Reset</button>
      <button type="button" id="cancelWishlist"><i class="fa-solid fa-xmark"></i> Cancel</button>
    </form>
  </div>
  <script>
    var userId = <?php echo json_encode(htmlspecialchars($_SESSION['userId'], ENT_QUOTES, 'UTF-8')); ?>;
    var firstName = '<?php echo $first_name; ?>';
  </script>
</body>
</html>
