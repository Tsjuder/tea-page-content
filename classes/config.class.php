<?php
/**
 * @package Tea Page Content
 * @version 1.2.1
 */

class TeaPageContent_Config {
	private static $_instance;
	private $_config;

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
    	$config = include(TEA_PAGE_CONTENT_PATH . '/config.php');

    	if(is_array($config)) {
    		self::$_instance->_config = apply_filters('tpc_config_array', $config);

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
	 * @param string $params Dot-separated path to needly parameter
	 * @param string|array $except Determine parameters that will be excluded
	 * 
	 * @return mixed|null
	 */
	public function get($param, $except = null) {
		$pieces = explode('.', $param);
		$piecesCount = count($pieces);

		$result = null;
		$stack = $this->_config;
		for ($i = 0; $i <= $piecesCount; $i++) {
			if(isset($pieces[$i]) && isset($stack[$pieces[$i]])) {
				$stack = $stack[$pieces[$i]];
				continue;
			} elseif($i == $piecesCount) {
				if(is_array($except) && ($intersect = array_intersect($except, array_keys($stack)))) {
					foreach ($intersect as $key) {
						unset($stack[$key]);
					}
				} elseif(is_string($except) && array_key_exists($except, $stack)) {
					unset($stack[$except]);
				}
				
				$result = $stack;
			}

			break;
		}

		return $result;
	}
}