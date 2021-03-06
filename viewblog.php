<?php

	include_once('php/dbLink.php');
	include_once('php/user.php');
	include_once('php/core.php');
	include_once('php/blog.php');
	include_once('php/comment.php');

	// Init variables
	$user = new User(NULL, NULL, NULL, false);
	$errorMessage = '';
	$blog = NULL;
	$commentList = NULL;

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

	// Check if blog id is set
	if (isset($_GET['id'])) {

		// Parse id
		$id = mysql_real_escape_string($_GET['id']);

		if (!is_numeric($id)) {

			// Id was not a number, throw an error
			setResultCode(14);
			header("Location: blog.php");
			exit();

		}

		// Check if id corresponds with a blog from the database
		if (!BLog::idExists($id, $dbLink)) {

			// Id doesn't exist, throw an error
			setResultCode(14);
			header("Location: blog.php");
			exit();

		}

		// Get blog from the database
		$blog = Blog::getBlogById($id, $dbLink);
		
		// Check if a comment has been submitted
		if (isset($_POST['comment-submit'])) {

			// Check if the comment isn't empty
			if (isset($_POST['fansite-comment'])) {

				// Parse comment
				$comment = mysql_real_escape_string($_POST['fansite-comment']);
				$comment = strip_tags($comment);

				// Post comment in the database
				$blog->postComment($comment, $user, $dbLink);

			}

		}

		// Get comment list
		$commentList = $blog->getComments($dbLink);

	} else {

		setResultCode(14);
		header("Location: blog.php");
		exit();

	}

	$errorMessage = getResultCodeMessage(getResultCode());
	clearResultCode();

?>

<!DOCTYPE html>
<html>
	<head>
		<title>E D E N</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="css/viewblog.css">
		<link href='https://fonts.googleapis.com/css?family=Roboto:100,300' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Lato:400,300' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<div id="page-top">
			<ul id="navbar">
				<li><a href="index.php">Home</a></li>
				<li><a href="blog.php" id="active">Blog</a></li>
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
			<div class="header header-1"></div>
			<div id="blog-box">
				<div id="blog-item">
					<div id="blog-info">
						<span id="blog-title"><?php echo $blog->getTitle(); ?></span>
						<span id="blog-date"><?php echo $blog->getDate(); ?></span>
					</div>
					<div id="blog-content">
						<?php echo $blog->getContent(); ?>
					</div>
					<div id="comment-box">
						<div id="comment-title">Comments</div>
						<div id="comments">
							<?php

								foreach ($commentList as $comment) {
									
									echo '<div class="comment-item">';
									echo '<div class="comment-username">' . User::getUserById($comment->getUserId(), $dbLink)->getUsername() . '</div>';
									echo '<div class="comment-content">' . stripslashes($comment->getContent()) . '</div>';
									echo '</div>';

								}

							?>
						</div>
						<?php

							if ($user->isValidated()) {

						?>
						<div id="post-comment-box">
							<form action="viewblog.php?id=<?php echo $blog->getId(); ?>" method="POST">
								<div id="post-comment-title">Leave a comment</div>
								<textarea name="fansite-comment" cols="40" rows="6" placeholder="I really like this blog!"></textarea>
								<input type="submit" name="comment-submit" value="Send" class="button button-success" />
							</form>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>