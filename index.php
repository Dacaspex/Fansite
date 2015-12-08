<?php

	include_once('php/dbLink.php');
	include_once('php/user.php');
	include_once('php/core.php');

	// Init variables
	$user = new User(NULL, NULL, NULL, false);
	$errorMessage = '';
	$errorCode = 0;

	// Session setup
	ini_set('session.cookie_httponly', 1);
	session_start();
	session_regenerate_id(true);

	// Check for an active session
	// Else check for login try
	if (isset($_SESSION['userId'])) {

		// Check if session is valid
		$user = User::getUserById($_SESSION['userId'], $dbLink);

		if (!$user->isValidated()) {

			// Session is not valid, kill the session and throw an error
			User::killSession();

			setRedirectCode(6);
			header('Location: index.php');
			exit();

		}

	} else if (isset($_POST['submit'])) {
		
		// Parse variables
		$username = mysql_real_escape_string($_POST['username_fansite']);
		$password = mysql_real_escape_string($_POST['password_fansite']);

		// Valiate input
		if (validateText($username) && validateText($password)) {

			// Check if the username exists
			if (!User::isUsernameAvailable($username, $dbLink)) {

				// Validate log in credentials
				$user = User::validate($username, $password, $dbLink);

				if ($user->isValidated()) {

					// Log in credentials are correct, log the user in
					$user->login();

					setRedirectCode(3);
					header('Location: index.php');
					exit();

				} else {

					// Password and username don't match, throw an error
					$errorCode = 3;

				}

			} else {

				// The username does not appear in the databse, throw an error
				$errorCode = 2;

			}

		} else {

			// Format is wrong, throw an error
			$errorCode = 1;

		}
	}

	switch (getRedirectCode()) {
		case 1:
			$errorMessage = '<div class="panel panel-success">Successfully registerd. Log in to enable more features on the site!</div>';
			break;

		case 2:
			$errorMessage = '<div class="panel panel-alert">Something went wrong while registering</div>';
			break;

		case 3:
			$errorMessage = '<div class="panel panel-success">Successfully logged in</div>';
			break;
		
		default:
			break;
	}

	clearRedirectCode();

	switch ($errorCode) {
		case 1:
			$errorMessage = '<div class="panel panel-alert">Username and password format are wrong</div>';
			break;

		case 2:
			$errorMessage = '<div class="panel panel-warning">This username does not exists (yet)</div>';
			break;

		case 3:
			$errorMessage = '<div class="panel panel-alert">The given password was incorrect</div>';
			break;
		
		default:
			break;
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>E D E N</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="css/index.css">
		<link href='https://fonts.googleapis.com/css?family=Roboto:100,300' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Lato:400,300' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<div id="page-top">
			<ul id="navbar">
				<li><a href="index.php" id="active">Home</a></li>
				<li><a href="#">Music</a></li>
				<li><a href="blog.php">Blog</a></li>
				<?php
					if (!$user->isValidated()) {
				?>
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
				<?php
					} else {
				?>
				<li class="pull-right">
					<a href="logout.php">Log out</a>
				</li>
				<li class="pull-right">
					<a class="no-link">Logged in as <strong><?php echo $user->getUsername(); ?></strong></a>
				</li>
				<?php
					}
				?>
			</ul>
		</div>
		<div id="hero-unit">
			<div id="inner-wrapper">
				<div id="message-box">
					<span id="name">E D E N</span><br />
					<span id="qoute">Music for you</span>
				</div>
			</div>
		</div>
		<div id="newsletter-box">
			<form id="newsletter-form">
				<span>Sign in for the newsletter</span>
				<input type="text" placeholder="e-mail"/>
				<input class="button button-success button-inline" type="submit" value="Send">
			</form>
		</div>
		<div id="wrapper">
			<div id="margin-fix"></div>
			<?php

				echo $errorMessage;

			?>
			<div id="col-1">
				<div class="header header-1">
					Welcome
				</div>
				<p>
					Jonathan Ng, going by the name of <strong>EDEN</strong> is a Electro, House and Melodic Dubstep producer, originating in Hong Kong SAR but now lives in Dublin, Ireland. EDEN so far has two releases on Monstercat which are 'Scribble' and 'The Fire' which are both in collaboration with Puppet. Since then, The Eden Project has mentioned on social media that he would love to be part of the Monstercat family, however wants to remain with his current music label, NoCopyRightSounds for a while longer.
				</p>
			</div>
			<div id="col-2">
				<div class="header header-1">
					Latest music
				</div>
				<p>
					
				</p>
			</div>
		</div>
	</body>
</html>