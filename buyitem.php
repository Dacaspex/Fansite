<?php

	include_once('php/dbLink.php');
	include_once('php/user.php');
	include_once('php/core.php');
	include_once('php/product.php');

	// Init variables
	$user = new User(NULL, NULL, NULL, false);

	// Session setup
	ini_set('session.cookie_httponly', 1);
	session_start();
	session_regenerate_id(true);

	if (isset($_SESSION['userId'])) {

		// Check if session is valid
		$user = User::getUserById($_SESSION['userId'], $dbLink);

		if (!$user->isValidated()) {

			// Session is not valid, throw error
			// setRedirectCode(99);
			User::killSession();

		}

		// Check if product id is set
		if (isset($_GET['id'])) {

			// Parse id
			$id = mysql_real_escape_string($_GET['id']);

			if (!is_numeric($id)) {

				// setRedirectCode(99);
				header('Location: index.php');
				exit();

			}

			// Get the product and check if the id was valid
			$product = Product::getProductById($id, $dbLink);

			if (is_null($product)) {

				// Id didn't match product, throw an error
				// setRedirectCode(99);
				header('Location: shop.php');
				exit();

			}

			// All is good, add an order to the 'orders' table
			$product->buyProduct($user, $dbLink);

			// Redirect to the shop page
			// setRedirectCode(99);
			header('Location: shop.php');
			exit();

		}

	}

?>