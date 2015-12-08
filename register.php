<?php

	include_once('php/dbLink.php');
	include_once('php/user.php');
	include_once('php/core.php');

	// Init variables
	$user = NULL;
	$errorCode = 0;

	$username = '';
	$password = '';
	$passwordCheck = '';
	$email = '';

	// Session setup
	ini_set('session.cookie_httponly', 1);
	session_start();
	session_regenerate_id(true);

	// Check for an active session
	// Else check for login try
	if (isset($_SESSION['userId'])) {

		// Check if session is valid and return a user
		$user = User::getUserById($_SESSION['userId'], $dbLink);

		if (!$user->isValidated()) {
			
			// Session is not valid, kill the session and throw an error
			User::killSession();

			setRedirectCode(6);
			header('Location: index.php');
			exit();

		} else {
			
			// Redirect to the index page
			setRedirectCode(7);
			header('Location: index.php');
			exit();

		}

	} else if (isset($_POST['submitRegister'])) {

		// Parse variables
		$username = mysql_real_escape_string($_POST['username123']);
		$password = mysql_real_escape_string($_POST['password123']);
		$passwordCheck = mysql_real_escape_string($_POST['passwordCheck123']);
		$email = mysql_real_escape_string($_POST['email123']);

		// Check if the input is correct
		if (validateText($username) && validateText($password) && validateEmail($email)) {

			// Check for username availability
			if (User::isUsernameAvailable($username, $dbLink)) {

				// Check if passwords match
				if ($password == $passwordCheck) {

					$succes = User::register($username, $password, $email, $dbLink);

					if ($succes) {

						// Successfully reigstered the user, redirect to the index page
						setRedirectCode(1);
						header('Location: index.php');

					} else {

						// Something went wrong, throw an error
						setRedirectCode(2);
						header('Location: index.php');

					}

				} else {

					// Passwords didn't match, throw an error
					$errorCode = 1;

				}

			} else {

				// Username is not available, throw an error
				$errorCode = 3;

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
				<li><a href="#">Music</a></li>
				<li><a href="blog.php">Blog</a></li>
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
			<?php

				switch ($errorCode) {
					case 1:
						echo '<div class="panel panel-alert">Could not register: Passwords didn\'t match</div>';
						break;

					case 2:
						echo '<div class="panel panel-warning">Could not register: Invaled username, password and/or e-mail adress<br /><br />Only numbers and letters are allowed in your username and password</div>';
						break;

					case 3:
						echo '<div class="panel panel-warning">That username is not available</div>';
						break;
					
					default:
						break;
				}

			?>
			<form action="register.php" method="POST" id="register-form">
				<table>
					<tr>
						<td class="label">Username</td>
						<td class="input"><input type="text" name="username123" placeholder="Username" <?php echo 'value="' . $username . '"'; ?> /></td>
					</tr>
					<tr>
						<td>Password</td>
						<td><input type="password" name="password123" placeholder="Password" <?php echo 'value="' . $password . '"'; ?> /></td>
					</tr>
					<tr>
						<td>Check password</td>
						<td><input type="password" name="passwordCheck123" placeholder="Password" <?php echo 'value="' . $passwordCheck . '"'; ?> /></td>
					</tr>
					<tr>
						<td>E-mail adress</td>
						<td><input type="text" name="email123" placeholder="E-mail" <?php echo 'value="' . $email . '"'; ?> /></td>
					</tr>
					<tr>
						<td class="label"></td>
						<td class="input"><input type="submit" name="submitRegister" value="Register" id="register-button" /></td>
					</tr>
				</table>
			</form>
			<div id="register-information">
				<div class="header header-4">
					Benefits of registering
				</div>
				<ul id="register-information-ul">
					<li>Ability to reply to blog posts</li>
					<li>Being able to purchase goodies in the shop</li>
					<li>Be awesome</li>
				</ul>
			</div>
		</div>
	</body>
</html>