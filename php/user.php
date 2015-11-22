<?php

	class User {
		private $userId;
		private $username;

		function __construct($userId, $username, $email) {

			$this->userId = $userId;
			$this->username = $username;
			$this->email = $email;

		}

		public function login() {

			$_SESSION['userId'] = $this->userId;

		}

		public function logout() {

			unset($_SESSION['userId']);

		}

		public static function getUserById($userId, $dbLink) {

			$stmt = $dbLink->prepare("SELECT id, username, email FROM users WHERE id = (?)");

			$stmt->bind_param('s', $userId);
			$stmt->execute();
			$stmt->bind_result($resultId, $resultUsername, $resultEmail);

			while ($stmt->fetch()) {

				$stmt->close();
				return new User($resultId, $resultUsername, $resultEmail);

			}

			$stmt->close();
			return NULL;

		}

		public static function register($username, $password, $email, $dbLink) {

			$hashedPassword = generateHash($password);

			$stmt = $dbLink->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");

			$stmt->bind_param('sss', $username, $hashedPassword, $email);
			$stmt->execute();
			$stmt->close();

			return true;

		}

		public static function isUsernameAvailable($username, $dbLink) {

			$stmt = $dbLink->prepare("SELECT username FROM users  WHERE username = (?)");

			$stmt->bind_param('s', $username);
			$stmt->execute();
			$stmt->bind_result($resultUsername);

			while ($stmt->fetch()) {

				$stmt->close();
				return false;

			}

			$stmt->close();
			return true;

		}

		public static function validate($username, $password, $dbLink) {

			$stmt = $dbLink->prepare("SELECT password, id FROM users WHERE username = (?)");

			$stmt->bind_param('s', $username);
			$stmt->execute();
			$stmt->bind_result($resultPassword, $resultId);

			while ($stmt->fetch()) {

				if (checkHash($password, $resultPassword)) {

					$stmt->close();
					return User::getUserById($resultId, $dbLink);

				} else {

					$stmt->close();
					return NULL;

				}

			}

			$stmt->close();
			return NULL;

		}

		public static function killSession() {

			unset($_SESSION['userId']);

		}
	}

?>