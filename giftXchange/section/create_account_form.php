<!DOCTYPE html>
<html>
<head>
	<title>User Registration</title>
	<link rel="stylesheet" type="text/css" href="\giftXchange\styles\create_account_form.css">
</head>
<body>
	<div class="background-image"></div>
	<div style="max-width: 500px; margin: auto;">
	<div class="container">
	<div class="container">
		<h1>User Registration</h1>
		<form action="/giftXchange/section/create_account.php" method="POST">
			<label for="first_name">First Name:</label>
			<input type="text" id="first_name" name="first_name" pattern="[A-Za-z]+" required>
			<label for="last_name">Last Name:</label>
			<input type="text" id="last_name" name="last_name" pattern="[A-Za-z]+" required>
			<label for="email">Email:</label>
			<input type="email" id="email" name="email" required>
			<label for="username">Username:</label>
			<input type="text" id="username" name="username" required>
			<label for="password">Password:</label>
			<input type="password" id="password" name="password" required>
			<label for="confirm_password">Confirm Password:</label>
			<input type="password" id="confirm_password" name="confirm_password" required>
			<button type="submit" class="btn">Create Account</button>
		</form>
	</div>
</body>
</html>
