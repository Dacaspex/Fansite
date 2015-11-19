<?php

	function validateText($text) {

		if ($text == "") {
			return false;
		}

		if (!ctype_alnum($text)) {
			return false;
		}

		if (ctype_space($text)) {
			return false;
		}

		return true;

	}

	function validateEmail($email) {

	}

?>