<?php

class CDN {
	
	private static $_url = array();

	private static $_cache;

	public static function debug($v) {
		 if ($v)
		 	self::$_cache = '?v=' . bin2hex(random_bytes(10));
		 else
		 	self::$_cache = '';
	}

	public static function add($k, $v) {
		self::$_url[$k]		=		$v;
	}

	public static function for($k, $v = false) {
		if (!$v) 
			return self::$_url[$k];
		else
			return self::$_url[$k] . DIRECTORY_SEPARATOR . $v . self::$_cache;
	}

}