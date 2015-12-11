<?php
	
	include_once('php/core.php');

	// Session setup
	ini_set('session.cookie_httponly', 1);
	session_start();
	session_regenerate_id(true);
	
	unset($_SESSION['userId']);

	setResultCode(11);
	header('Location: index.php');
	exit();
?>