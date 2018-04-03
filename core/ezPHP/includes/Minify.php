<?php

class Minify {
	/*
	 *	Credits: http://stackoverflow.com/questions/6225351/how-to-minify-php-page-html-output
	 */
	private static function __minify($buffer) {

		    $search = array(
		        '/\>[^\S ]+/s',
		        '/[^\S ]+\</s',
		        '/(\s)+/s'
		    );

		    $replace = array(
		        '>',
		        '<',
		        '\\1'
		    );

		    $buffer = preg_replace($search, $replace, $buffer);

		    return $buffer;
		}

	public static function init() {
		ob_start("self::__minify");
	}

}

?>