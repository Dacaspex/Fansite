<?php

	function validateText($text) {

		if ($text == "")
			return false;

		if (!ctype_alnum($text))
			return false;

		if (ctype_space($text))
			return false;

		return true;

	}

	function validateEmail($email) {

		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

			return true;

		} else {

			return false;

		}

	}

	function generateHash($password) {

		$cost = 5;
		$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');

		$salt = sprintf("$2a$%02d$", $cost) . $salt;

		$hash = crypt($password, $salt);

		return $hash;

	}

	function checkHash($password, $hash) {

		return $hash == crypt($password, $hash);

	}

	function setResultCode($resultCode) {

		$_SESSION["resultCode"] = $resultCode;

	}

	function getResultCode() {

		if (isset($_SESSION["resultCode"])) {

			return $_SESSION["resultCode"];

		} else {

			return -1;

		}

	}

	function getResultCodeMessage($resultCode) {

		switch ($resultCode) {
			case 1:
				return '<div class="panel panel-success">Successfully registerd. Log in to enable more features on the site!</div>';
				break;
			case 2:
				return '<div class="panel panel-alert">Something went wrong while registering</div>';
				break;
			case 3:
				return '<div class="panel panel-warning">The password and password check don\'t match</div>';
				break;
			case 4:
				return '<div class="panel panel-warning">That username is not available</div>';
				break;
			case 5:
				return '<div class="panel panel-warning">The format of your username, password and or email adress are wrong. <br><br>You can only use letters and numbers in your username and password</div>';
				break;
			case 6:
				return '<div class="panel panel-alert">Something went wrong while checking your log in credentials</div>';
				break;
			case 7:
				return '<div class="panel panel-warning">You can\'t register while logged in</div>';
				break;
			case 8:
				return '<div class="panel panel-success">Successfully logged in</div>';
				break;
			case 9:
				return '<div class="panel panel-warning">Your password is incorrect</div>';
				break;
			case 10:
				return '<div class="panel panel-warning">That username doesn\'t exist (yet) on this website. Click <a href="register.php">here</a> to create an account</div>';
				break;
		}

	}

	function clearResultCode() {

		unset($_SESSION["resultCode"]);

	}

?>