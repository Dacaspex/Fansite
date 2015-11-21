<?php

	session_start();
	session_regenerate_id();

	include_once('php/dbLink.php');
	include_once('php/user.php');
	include_once('php/core.php');

	// Init variables
	$user = NULL;
	$errorCode = 0;

	// Check for an active session
	// Else check for login try
	if (isset($_SESSION['userId'])) {

		// Check if session is valid and return a user
		$user = User::getUserById($_SESSION['userId']);

		if (is_null($user)) {
			// Session was not valid, throw error


		} else {
			// Redirect to index page


		}

	} else if (isset($_POST['submitRegister'])) {

		// Parse variables
		$username = mysql_real_escape_string($_POST['username123']);
		$password = mysql_real_escape_string($_POST['password123']);
		$passwordCheck = mysql_real_escape_string($_POST['passwordCheck123']);
		$email = mysql_real_escape_string($_POST['email123']);

		// Check if the input is correct
		if (validateText($username) && validateText($password) && validateEmail($email)) {

			// Check if passwords match
			if ($password == $passwordCheck) {

				User::register($username, $password, $email, $dbLink);


				header('Location: index.php');

			} else {

				// Passwords didn't match, throw an error
				$errorCode = 1;

			}

		} else {

			// Invaled credentials, throw an error
			$errorCode = 2;

		}

	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>E D E N</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="css/register.css">
		<link href='https://fonts.googleapis.com/css?family=Roboto:100,300' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<div id="page-top">
			<ul id="navbar">
				<li><a href="index.php">Home</a></li>
				<li><a href="#">About</a></li>
				<li><a href="#">Blog</a></li>
				<li class="pull-right" id="sub-menu-toggle">
					<a href="#" class="no-link">Login / Register</a>
					<div id="sub-menu">
						<div id="account-form">
							<div class="header header-6">
								Log in
							</div>
							<form action="index.php" method="POST">
								<input type="text" name="username_fansite" placeholder="Username" />
								<input type="submit" name="submit" value="Log in" id="login-button"/>
								<div id="password-input">
									<input type="password" name="password_fansite" placeholder="Password" id="password-input"/>
								</div>
							</form>
							<span>Or <a href="register.php">register</a></span>
						</div>
					</div>
				</li>
			</ul>
		</div>
		<div id="margin-fix"></div>
		<div id="wrapper">
			<div class="header header-1">
				Register
			</div>
			<form action="register.php" method="POST">
				<table>
					<tr>
						<td class="label">Username</td>
						<td class="input"><input type="text" name="username123" placeholder="Username" /></td>
					</tr>
					<tr>
						<td>Password</td>
						<td><input type="password" name="password123" placeholder="Password" /></td>
					</tr>
					<tr>
						<td>Check password</td>
						<td><input type="password" name="passwordCheck123" placeholder="Password" /></td>
					</tr>
					<tr>
						<td>E-mail adress</td>
						<td><input type="text" name="email123" placeholder="E-mail" /></td>
					</tr>
					<tr>
						<td class="label"></td>
						<td class="input"><input type="submit" name="submitRegister" value="Register" id="register-button" /></td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>