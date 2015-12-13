<?php

	// Trying to establish a DB connection
	$dbAdress = "localhost";
	$dbUser = "root";
	$dbPassword = "";
	$dbDatabaseName = "fansite";
	
	$dbLink = new mysqli($dbAdress, $dbUser, $dbPassword, $dbDatabaseName);

	if ($dbLink->connect_error) {

		// Error occured while connecting, printing error
		echo "Error: Unable to connect" . PHP_EOL;
		echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
		echo "Error message: " . mysqli_connect_error() . PHP_EOL;
		exit();

	}
?>