<?php

class URL {
	
	private static $_url = array();

	public static function add($k, $v) {
		self::$_url[$k]		=		$v;
	}

	public static function for($k) {
		return self::$_url[$k];
	}

}