<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

/**
 * Functionality for determining client IP address.
 */
class Host
{
    /**
     * Whether to use proxy addresses or not.
     *
     * As default this setting is disabled - IP address is mostly needed to increase
     * security. HTTP_* are not reliable since can easily be spoofed. It can be enabled
     * just for more flexibility, but if user uses proxy to connect to trusted services
     * it's his/her own risk, only reliable field for IP address is $_SERVER['REMOTE_ADDR'].
     *
     * @var bool
     */
    protected static $useProxy = false;

    /**
     * List of trusted proxy IP addresses
     *
     * @var array
     */
    protected static $trustedProxies = [];

    /**
     * HTTP header to introspect for proxies
     *
     * @var string
     */
    protected static $proxyHeader = 'HTTP_X_FORWARDED_FOR';


    /**
     * Changes proxy handling setting.
     *
     * This must be static method, since validators are recovered automatically
     * at session read, so this is the only way to switch setting.
     *
     * @param  bool  $useProxy Whether to check also proxied IP addresses.
     * @return RemoteAddress
     */
    public static function setUseProxy($useProxy = true)
    {
        self::$useProxy = $useProxy;
        return self;
    }

    /**
     * Checks proxy handling setting.
     *
     * @return bool Current setting value.
     */
    public static function getUseProxy()
    {
        return self::$useProxy;
    }

    /**
     * Set list of trusted proxy addresses
     *
     * @param  array $trustedProxies
     * @return RemoteAddress
     */
    public static function setTrustedProxies(array $trustedProxies)
    {
        self::$trustedProxies = $trustedProxies;
        return self;
    }

    /**
     * Set the header to introspect for proxy IPs
     *
     * @param  string $header
     * @return RemoteAddress
     */
    public static function setProxyHeader($header = 'X-Forwarded-For')
    {
        self::$proxyHeader = self::$normalizeProxyHeader($header);
        return self;
    }

    /**
     * Returns client IP address.
     *
     * @return string IP address.
     */
    public static function getIpAddress()
    {
        $ip = self::getIpAddressFromProxy();
        if ($ip) {
            return $ip;
        }

        // direct IP address
        if (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }

        return '';
    }

    /**
     * Attempt to get the IP address for a proxied client
     *
     * @see http://tools.ietf.org/html/draft-ietf-appsawg-http-forwarded-10#section-5.2
     * @return false|string
     */
    protected static function getIpAddressFromProxy()
    {
        if (!self::$useProxy
            || (isset($_SERVER['REMOTE_ADDR']) && !in_array($_SERVER['REMOTE_ADDR'], self::$trustedProxies))
        ) {
            return false;
        }

        $header = self::$proxyHeader;
        if (!isset($_SERVER[$header]) || empty($_SERVER[$header])) {
            return false;
        }

        // Extract IPs
        $ips = explode(',', $_SERVER[$header]);
        // trim, so we can compare against trusted proxies properly
        $ips = array_map('trim', $ips);
        // remove trusted proxy IPs
        $ips = array_diff($ips, self::$trustedProxies);

        // Any left?
        if (empty($ips)) {
            return false;
        }

        // Since we've removed any known, trusted proxy servers, the right-most
        // address represents the first IP we do not know about -- i.e., we do
        // not know if it is a proxy server, or a client. As such, we treat it
        // as the originating IP.
        // @see http://en.wikipedia.org/wiki/X-Forwarded-For
        $ip = array_pop($ips);
        return $ip;
    }

    /**
     * Normalize a header string
     *
     * Normalizes a header string to a format that is compatible with
     * $_SERVER
     *
     * @param  string $header
     * @return string
     */
    protected static function normalizeProxyHeader($header)
    {
        $header = strtoupper($header);
        $header = str_replace('-', '_', $header);
        if (0 !== strpos($header, 'HTTP_')) {
            $header = 'HTTP_' . $header;
        }
        return $header;
    }
}