<?php

	include_once('php/dbLink.php');
	include_once('php/user.php');
	include_once('php/core.php');
	include_once('php/secureSession.php');

	// Init variables
	$user = new User(NULL, NULL, NULL, false);
	$errorMessage = '';
	$errorCode = 0;

	// Session setup
	SecureSession::start();
	SecureSession::setup();

	// Check for an active session
	// Else check for login try
	if (isset($_SESSION['userId'])) {

		// Check if session is valid
		$user = User::getUserById($_SESSION['userId'], $dbLink);

		if ($user->isValidated()) {

			// Session is not valid, throw error
			$errorCode = 4;
			User::killSession();

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

	switch ($errorCode) {
		
		default:
			break;
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>E D E N</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="css/blog.css">
		<link href='https://fonts.googleapis.com/css?family=Roboto:100,300' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Lato:400,300' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<div id="page-top">
			<ul id="navbar">
				<li><a href="index.php">Home</a></li>
				<li><a href="#">About</a></li>
				<li><a href="#" id="active">Blog</a></li>
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
		<div id="wrapper">
			<div id="margin-fix"></div>
			<div class="header header-1">
				Blog!
			</div>
			<div id="hero-unit">
				<div id="latest-blogs-box">
					<div class="header header-4">
						Blog
					</div>
					<ul id="latest-posts-ul">
						<li>Get to know what I am working on</li>
						<li>Get in touch with me </li>
					</ul>
				</div>
			</div>
			<div id="blog-box">
				<div class="blog-item">
					<div class="blog-info">
						<span class="blog-title">This is a title</span>
						<span class="blog-date">27 - 11 - 2015</span>
					</div>
					<div class="blog-content">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo...
						magna aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo...
						magna aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo...
					</div>
					<div class="blog-footer">
						<span class="blog-comments">
							11 comments on this blog. <a href="viewblog.php?id=0">Leave a comment or read more</a>
						</span>
					</div>
				</div>
				<div class="blog-item">
					<div class="blog-info">
						<span class="blog-title">This is a title</span>
						<span class="blog-date">27 - 11 - 2015</span>
					</div>
					<div class="blog-content">
						Lud exercitation ullamco laboris nisi ut aliquip ex ea commodo...
						magna aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo...
						quip ex ea commodo...
						magna aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco laboris nislore Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incidid
						proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
					</div>
					<div class="blog-footer">
						<span class="blog-comments">
							11 comments on this blog. <a href="viewblog.php?id=1">Leave a comment or read more</a>
						</span>
					</div>
				</div>
				<div class="blog-item">
					<div class="blog-info">
						<span class="blog-title">The first blog evaaaah!</span>
						<span class="blog-date">27 - 11 - 2015</span>
					</div>
					<div class="blog-content">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor inmmodo...
						magna aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodcid aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco laboris idunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo...
						magna aliqua. Ut enim ad minim veniam,
						d exercitation ullamco laboris nisi ut aliquip ex ea commodo...quis nostrsi ut aliquip ex ea commodo...
					</div>
					<div class="blog-footer">
						<span class="blog-comments">
							4 comments on this blog. <a href="viewblog.php?id=2">Leave a comment or read more</a>
						</span>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>