<?php
class CSRF {

	private static $_csrfName = "csrf";

	private static function generate($params = false) {
		$date = new DateTime();
		if (!isset($_SESSION[self::$_csrfName]) && !$params) {
			$_SESSION[self::$_csrfName] = bin2hex(openssl_random_pseudo_bytes(12));
			return $_SESSION[self::$_csrfName];
		} else if ($params) {
			if (isset($_SESSION[self::$_csrfName])) 
				unset($_SESSION[self::$_csrfName]);

			$salt = bin2hex(openssl_random_pseudo_bytes(12));
			$_SESSION[self::$_csrfName] = md5($salt . $params);
			return $_SESSION[self::$_csrfName];
		}
	}

	public static function check() { 
		if (isset($_SESSION[self::$_csrfName]) && isset($_POST['token'])) {
			if ($_POST['token'] == $_SESSION[self::$_csrfName]) {
				return true;	
			} else return false;
		} else if (isset($_SESSION[self::$_csrfName]) && isset($_GET['token'])) {
			if ($_GET['token'] == $_SESSION[self::$_csrfName]) {
				return true;
			} else return false;
		}
	}

	public static function token() {
		return $_SESSION[self::$_csrfName];
	}

	public static function init($name = "csrf") {
		self::$_csrfName = $name;
		self::generate();
	}
}