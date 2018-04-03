<?php

class ezPHP {

	protected $_vars = array();

	protected $_page = array();

	protected $_config = array(
		'secret'	=>		'default',
		'cache'		=>		'true',
		'cache_time'=>		2,
		'minify'	=>		false,
	);

	protected $_dir = array(
		'views'		=>		'/views',
		'cache'		=>		'/views/_cache',
	);

	public function __construct($secret = 'default') {
		if ($secret == 'default')
			throw new Exception('In order to use ezPHP, you must change the secret key!');
		else
			$this->_config['secret'] = $secret;

		require 'includes/Cache.php';
		require 'includes/Minify.php';

		spl_autoload_register(function ($class) {
		    require 'plugins/' . $class . '.php';
		});
	}
	
	public function __set($name, $value) {
        $this->_vars[$name] = $value;
    }
    
    public function assign($name, $value) {
    	$this->_vars[$name] = $value;
    }

    public function __get($name) {
        return $this->_vars[$name];
    }

	public function setConfig($array = array()) {
		foreach ($array as $k => &$v) {
			$this->_config[$k]	= $v;
		}
		return $this;
	}

	public function setDirs($array) {
		foreach($array as $k => $v) {
			$this->_dir[$k]		=		$v;
		}
		return $this;
	}

	public function getConfig($conf) {
		if ($conf != 'secret')
			return $this->_config[$conf];
		else
			throw new Exception('Denied from using Secret key in public setting!');
	}

	public function getDir($dir) {
		return $this->_dir[$dir];
	}

	public function debug() {
		return print_r($this);
	}
	/*
	 *	PAGE SETTINGS
	 */
	public function title($value) {
		return $this->_vars['title']	= $value;
	}

    /*
     *	PHP TEMPLATING ENGINE by CameronCT
	 *	Simple Caching - By: https://www.addedbytes.com/articles/for-beginners/output-caching-for-beginners/
	 */

    public function _cache($_script, $_file) {
		ob_start();
		include $_script;
	    $fp = fopen($_file, 'w'); 
	    fwrite($fp, ob_get_contents());
	    fclose($fp); 
	    ob_end_flush();
	}

	public function render($file) {
		$_local = $this->_dir['views'] . DIRECTORY_SEPARATOR . $file . '.phtml';

		if ($this->_config['minify'] == true)
			Minify::init();

		if (file_exists($_local)) {
			if ($this->_config['cache'] == true) {	

				$_cache = $this->_dir['cache'] . DIRECTORY_SEPARATOR . Cache::address($file) . '.php';

				if (file_exists($_cache) && time() - $this->_config['cache_time'] <= filemtime($_cache)) {
					clearstatcache();
					readfile($_cache);
				} else if (!file_exists($_cache) || time() - $this->_config['cache_time'] > filemtime($_cache)) {
					$this->_cache($_local, $_cache);
				}
			} else { 
				require $this->_dir['views'] . DIRECTORY_SEPARATOR . $file . '.phtml';
			}
		} else throw new Exception('Template ' . $_local . ' could not be found!');
	}

	/*
	 *	PHP CACHE
	 *
	 */
	public function clearCache() {
		return Cache::clean($this->_dir['cache']);
	}

}