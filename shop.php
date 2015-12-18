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

			// Session is not valid, throw error
			User::killSession();

			setResultCode(6);
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
		<link rel="stylesheet" type="text/css" href="css/shop.css">
		<link href='https://fonts.googleapis.com/css?family=Roboto:100,300' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Lato:400,300' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<div id="page-top">
			<ul id="navbar">
				<li><a href="index.php">Home</a></li>
				<li><a href="blog.php">Blog</a></li>
				<li><a href="shop.php" id="active">Shop</a></li>
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
							<form action="" method="POST">
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
		<div id="wrapper">
			<div id="margin-fix"></div>
			<?php

				echo $errorMessage;

			?>
			<div class="header header-1">
				Shop
			</div>
			<div id="shop-content">
				<div class="shop-section">
					<div class="header header-6">
						Albums
					</div>
					<div class="shop-row">
						<div class="product-item">
							<div class="product-image" type="album"></div>
							<div class="product-title">
								Album 1: Lu Upic
							</div>
							<?php if ($user->isValidated()) { ?>
							<a href="buyitem.php?id=1" class="button button-info">Buy</a>
							<?php } else { ?>
							<span>Log in to buy this</span>
							<?php } ?>
						</div>
						<div class="product-item">
							<div class="product-image" type="album"></div>
							<div class="product-title">
								Album 2: Unuther
							</div>
							<?php if ($user->isValidated()) { ?>
								<a href="buyitem.php?id=2" class="button button-info">Buy</a>
							<?php } else { ?>
							<span>Log in to buy this</span>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="shop-section">
					<div class="header header-6">
						Images
					</div>
					<div class="shop-row">
						<div class="product-item">
							<div class="product-image" type="image"></div>
							<div class="product-title">
								Eden Wallpaper Pack
							</div>
							<?php if ($user->isValidated()) { ?>
								<a href="buyitem.php?id=3" class="button button-info">Buy</a>
							<?php } else { ?>
							<span>Log in to buy this</span>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>