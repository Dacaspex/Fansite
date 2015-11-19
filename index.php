<?php
	
	session_start();
	session_regenerate_id();

	include_once('php/user.php');

	// Init variables
	$user = NULL;

	// Check for an active session
	// Else check for login try
	if (isset($_SESSION['userId'])) {

		// Check if session is valid and return a user
		$user = User::getUserById($_SESSION['userId']);

		if (is_null($user)) {

			// Session was not valid, throw error


		}

	} else if (isset($_POST['submit'])) {
		
		// Parse variables
		$username = mysql_real_escape_string($_POST['username_fansite']);



	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>E D E N</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="css/index.css">
		<link href='https://fonts.googleapis.com/css?family=Roboto:100,300' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<div id="page-top">
			<ul id="navbar">
				<li><a href="#" id="active">Home</a></li>
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
		<div id="hero-unit">
			<div id="inner-wrapper">
				<div id="message-box">
					<span id="name">E D E N</span><br />
					<span id="qoute">Music for you</span>
				</div>
			</div>
		</div>
		<div id="wrapper">
			<div class="panel panel-success">Successfully logged in</div>
			<div id="col-1">
				<form id="newsletter-box">
					<span>Sign in for the newsletter</span>
					<input type="text" />
					<input class="button button-success button-inline" type="submit" value="Send">
				</form>
			</div>
			<div id="col-2">
				<div class="header header-1">
					Welcome
				</div>
				<p>
					Jonathan Ng, going by the name of <strong>EDEN</strong> is a Electro, House and Melodic Dubstep producer, originating in Hong Kong SAR but now lives in Dublin, Ireland. EDEN so far has two releases on Monstercat which are 'Scribble' and 'The Fire' which are both in collaboration with Puppet. Since then, The Eden Project has mentioned on social media that he would love to be part of the Monstercat family, however wants to remain with his current music label, NoCopyRightSounds for a while longer.
				</p>
			</div>
		</div>
	</body>
</html>