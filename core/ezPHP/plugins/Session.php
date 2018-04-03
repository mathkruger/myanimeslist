<?php

class Session {

    private static $_sessionID;

    private static function create() {
        if (!isset($_SESSION['_sessionID'])) {
            self::$_sessionID = md5(uniqid(rand(), true));
            $_SESSION['_sessionID'] = self::$_sessionID;
        } else return $_SESSION['_sessionID'];
    }

	public static function add($k, $v) {
        if (!isset($_SESSION['_sessionID']))
            self::create();
            
        $_SESSION[$k] = $v;
	}

	public static function edit($k) {
		$_SESSION[$k] = $v;
	}

    public static function remove($k) {
        unset($_SESSION[$k]);
    }

}