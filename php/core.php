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

	function clearResultCode() {

		unset($_SESSION["resultCode"]);

	}

?>