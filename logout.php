<?php
	
	include_once('php/core.php');

	session_start();

	unset($_SESSION['userId']);

	setRedirectCode(5);
	header('Location: index.php');
	exit();
?>