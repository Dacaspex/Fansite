<?php

	include_once('php/dbLink.php');
	include_once('php/user.php');
	include_once('php/core.php');

	// Init variables
	$user = new User(NULL, NULL, NULL, false);
	$errorMessage = '';

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
			setResultCode(6);

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
					setResultCode(8);

				} else {

					// Password and username don't match, throw an error
					setResultCode(9);

				}

			} else {

				// The username does not appear in the databse, throw an error
				setResultCode(10);

			}

		} else {

			// Format is wrong, throw an error
			setResultCode(5);

		}
	}

	$errorMessage = getResultCodeMessage(getResultCode());
	clearResultCode();

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
				<li><a href="blog.php">Blog</a></li>
				<li><a href="shop.php">Shop</a></li>
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
								<input type="text" name="username_fansite" placeholder="Username" tabindex="1" />
								<input type="submit" name="submit" value="Log in" id="login-button" tabindex="3" />
								<div id="password-input">
									<input type="password" name="password_fansite" placeholder="Password" id="password-input" tabindex="2" />
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
		<div id="hero-box">
			Welcome to my website
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
					<a href="https://www.youtube.com/watch?v=cyDOUPz0hpA" class="music-link">Billie Jean</a>
					<a href="https://www.youtube.com/watch?v=YthChN1Wq8M" class="music-link">Wake up</a>
					<a href="https://www.youtube.com/watch?v=syXq0ICfFDg" class="music-link">XO</a>
					<a href="https://www.youtube.com/watch?v=v8KPX-KPsFU" class="music-link">Fumes</a>
					<a href="https://www.youtube.com/watch?v=0pVABElms84" class="music-link">End Credits</a>
					<a href="https://www.youtube.com/watch?v=fwbtB4vRXHg" class="music-link">Nocturne</a>
					<a href="https://www.youtube.com/watch?v=f1eMI0d-1Hs" class="music-link">Gravity</a>
				</p>
			</div>
		</div>
	</body>
</html>