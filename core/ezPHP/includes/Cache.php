<?php

class Cache extends ezPHP {

	/*
	 *	CREDITS: http://stackoverflow.com/questions/11267086/php-unlink-all-files-within-a-directory-and-then-deleting-that-directory
	 */

	public static function address($file, $debug = false) {
		if (!session_id()) {
			$var = hash('sha256', $file);
			return $var;
		} else {
			$_SESSION['_ezSession']		=		session_id();
			$var = hash('sha256', $file . '.' . Host::getIpAddress() . '.' . $_SESSION['_ezSession']);
			return $var;
		}
	}

	public static function clean($dir) {
		foreach (glob($dir . DIRECTORY_SEPARATOR . "*.*") as $filename) {
		    @unlink(self::address($filename));
		}
	}

}

?>