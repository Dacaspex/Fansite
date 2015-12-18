<?php

	class User {

		private $userId;
		private $username;
		private $validated;

		function __construct($userId, $username, $email, $validated) {

			$this->userId = $userId;
			$this->username = $username;
			$this->email = $email;
			$this->validated = $validated;

		}

		public function getId() {

			return $this->userId;

		}

		public function getUsername() {

			return $this->username;

		}

		public function login() {

			$_SESSION['userId'] = $this->userId;

		}

		public function logout() {

			unset($_SESSION['userId']);

		}

		public function isValidated() {

			return $this->validated;

		}

		public static function getUserById($userId, $dbLink) {

			// Prepare and execute SQL
			$stmt = $dbLink->prepare("SELECT id, username, email FROM users WHERE id = (?)");

			$stmt->bind_param('s', $userId);
			$stmt->execute();
			$stmt->bind_result($resultId, $resultUsername, $resultEmail);

			// Check respons
			while ($stmt->fetch()) {

				// Respons was valid, return a user
				$stmt->close();
				return new User($resultId, $resultUsername, $resultEmail, true);

			}

			// Repsons wasn't valid, return an invalid user
			$stmt->close();
			return new User(NULL, NULL, NULL, false);

		}

		public static function register($username, $password, $email, $newsletterSelected, $dbLink) {

			$hashedPassword = generateHash($password);

			$stmt = $dbLink->prepare("INSERT INTO users (username, password, email, newsletter_selected) VALUES (?, ?, ?, ?)");

			$stmt->bind_param('sssi', $username, $hashedPassword, $email, $newsletterSelected);
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
					return new User(NULL, NULL, NULL, false);

				}

			}

			$stmt->close();
			return new User(NULL, NULL, NULL, false);

		}

		public static function killSession() {

			unset($_SESSION['userId']);

		}
	}

?>