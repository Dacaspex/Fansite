<?php

	class User
	{
		private $userId;
		private $username;

		function __construct($userId, $username) {

			$this->userId = $userId;
			$this->username = $username;

		}

		public static function getUserById($userId, $dbLink)
		{
			$result = mysqli_query($dbLink, "SELECT * FROM users WHERE id='" . $userId . "'");

			if (mysqli_num_rows($result) == 1) {

				foreach ($result as $row) {
					$userId = $row["userId"];
					$username = $row["username"];
				}

				mysqli_free_result($result);
				return new User($userId, $first_name, $last_name);

			} else {

				mysqli_free_result($result);
				return NULL;

			}
		}

		public static function validateUser($username, $password, $dbLink)
		{
			$result = mysqli_query($dbLink, "SELECT * FROM users WHERE first_name='" . $username . "' AND password='" . $password . "'");

			if (mysqli_num_rows($result) == 1) {

				foreach ($result as $row) {
					$userId = $row["userId"];
					$username = $row["username"];
				}

				mysqli_free_result($result);
				return new User($user_id, $first_name, $last_name);
			} else {
				mysqli_free_result($result);
				return NULL;
			}
		}
	}

?>