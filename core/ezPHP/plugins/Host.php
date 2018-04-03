<?php

/*
 *  Pulls information about a User such as IP and MAC Address
 */
class Host {

    /*
     *  Grabs IP Address using Env Variables
     */
    private static function getEnvIP() {
        $envIPAddress = '';
		if (getenv('HTTP_CF_CONNECTING_IP'))
			$envIPAddress = getenv('HTTP_CF_CONNECTING_IP');
	    else if (getenv('HTTP_CLIENT_IP'))
	        $envIPAddress = getenv('HTTP_CLIENT_IP');
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $envIPAddress = getenv('HTTP_X_FORWARDED_FOR');
	    else if(getenv('HTTP_X_FORWARDED'))
	        $envIPAddress = getenv('HTTP_X_FORWARDED');
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $envIPAddress = getenv('HTTP_FORWARDED_FOR');
	    else if(getenv('HTTP_FORWARDED'))
	       $envIPAddress = getenv('HTTP_FORWARDED');
	    else if(getenv('REMOTE_ADDR'))
	        $envIPAddress = getenv('REMOTE_ADDR');
	    else
	        $envIPAddress = '0.0.0.0';
	    if (filter_var($envIPAddress, FILTER_VALIDATE_IP) === false)
	    	$envIPAddress = '0.0.0.0';

		(isset($_SERVER["HTTP_CF_CONNECTING_IP"])?$_SERVER["HTTP_CF_CONNECTING_IP"]:$_SERVER['REMOTE_ADDR']);

	    return $envIPAddress;
    }

    /*
     *  Make's sure that the IP that was given is not an iPv6, and if it is then convert to regular IP
     *  Credits: https://stackoverflow.com/questions/12435582/php-serverremote-addr-shows-ipv6
     */
    public static function getIP() {
        $envIP = self::getEnvIP();

        $ExplodeIP = explode(":", $envIP);

		if (isset($ExplodeIP[1])) {
			$v4mapped_prefix_hex = '00000000000000000000ffff';
			$v4mapped_prefix_bin = pack("H*", $v4mapped_prefix_hex);

			$addr = $_SERVER['REMOTE_ADDR'];
			$addr_bin = inet_pton($addr);
			if( $addr_bin === FALSE ) {
                return '0.0.0.0';
			}

			if( substr($addr_bin, 0, strlen($v4mapped_prefix_bin)) == $v4mapped_prefix_bin) {
			  $addr_bin = substr($addr_bin, strlen($v4mapped_prefix_bin));
			}

			$addr = inet_ntop($addr_bin);
			return $addr;
		} else { 
			return $envIP;
		}
    }

    /*
     *  Support for people who are still using Zend's Host class and have a hard time switching
     */
    public static function getIpAddress() {
        return self::getIP();
    }
}