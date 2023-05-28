<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" type="text/css" href="\giftXchange\styles\reset_password_form.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <script>
        function delayPasswordMasking(id) {
            var input = document.getElementById(id);
            input.type = 'text';
            setTimeout(function() {
                input.type = 'password';
            }, 250);  // 1000 ms delay = 1 second
        }
    </script>
</head>
<body>
<div class="background-image"></div>
<div  id="container_div">
    <div class="container">
        <div class="container">
            <form action="/giftXchange/section/admin_reset_user_password.php" method="POST">
                <input type="hidden" id="username" name="username" value="<?php echo isset($_GET['username']) ? $_GET['username'] : '' ?>">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" oninput="delayPasswordMasking('new_password')" required>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" oninput="delayPasswordMasking('confirm_password')" required>
                <button type="submit" class="btn">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>
