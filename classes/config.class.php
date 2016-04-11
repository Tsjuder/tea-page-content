<?php
/**
 * @package Tea Page Content
 * @version 1.1.1
 */

class TeaPageContent_Config {
	private static $_instance;
	private static $_config;

	/**
	 * Private constructor
	 * @return void
	 */
	private function __construct() {}
    private function __clone() {
    	return self::$_instance;
    }
    private function __wakeup() {
    	return self::$_instance;
    }

    /**
     * Init config storage at once,
     * if config file is exists and correct
     * 
     * @return boolean
     */
    private static function initialize() {
    	include(TEA_PAGE_CONTENT_PATH . '/config.php');

    	if(isset($config)) {
    		self::$_config = apply_filters('tpc_config_array', $config);

    		unset($config);

    		return true;
    	}

    	return false;
    }

    /**
     * Enter point. Returns the current instance of singleton.
     * Also calls init function, if instance is null
     * 
     * @return object
     */
	public static function getInstance() {
		if(is_null(self::$_instance)) {
			self::$_instance = new self;

			self::initialize();
		}

		return self::$_instance;
	}

	/**
	 * Public getter. Returns a config parameter,
	 * if it exists. In other case returns null.
	 * 
	 * @param string $param Dot-separated path to needly parameter
	 * @return mixed|null
	 */
	public static function get($param, $except = null) {
		$pieces = explode('.', $param);
		$piecesCount = count($pieces);

		$result = null;
		$stack = self::$_config;
		for ($i = 0; $i <= $piecesCount; $i++) {
			if(isset($pieces[$i]) && isset($stack[$pieces[$i]])) {
				$stack = $stack[$pieces[$i]];
				continue;
			} elseif($i == $piecesCount) {
				if($except && array_key_exists($except, $stack)) {
					unset($stack[$except]);
				}
				
				$result = $stack;
			}

			break;
		}

		return $result;
	}
}