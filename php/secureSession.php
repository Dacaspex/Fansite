<?php

	/**
	* Everything in one place for enhancing session security
	*/
	class SecureSession {

		public static function setup() {

			// Set apppropiate settings for enhanced security
			ini_set('session.cookie_httponly', 1);
			ini_set('session.session.use_only_cookies', 1);
			ini_set('session.cookie_secure', 1);

		}

		public static function start() {

			session_start();
			session_regenerate_id(true);

		}

		public static function destroy() {

			session_destroy();

		}
	}
?>