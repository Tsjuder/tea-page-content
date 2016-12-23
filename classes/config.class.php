<?php
/**
 * @package Tea Page Content
 * @version 1.2.3
 */

class TeaPageContent_Config {
	private static $_instance;

	private $_config;
	private $_helper;
	private $_map;

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
    	$config = require_once(TEA_PAGE_CONTENT_PATH . '/config.php');
    	$map = require_once(TEA_PAGE_CONTENT_PATH . '/config.map.php');

    	// Create new helper instance
		self::$_instance->_helper = new TeaPageContent_Helper;

    	if(is_array($config)) {
    		self::$_instance->_config = apply_filters('tpc_config_array', $config);

    		if(is_array($map)) {
    			self::$_instance->_map = apply_filters('tpc_config_map_array', $map);
    		}

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
	 * Return the config map which load previously.
	 * 
	 * @return array
	 */
	public function get_config_map() {
		return $this->_map;
	}

	public function sanitizeOption($param, $value) {
		if(!isset($this->_map[$param])) {
			return $value;
		}

		$filter = 'safehtml'; // @todo via config
		if(isset($this->_map[$param]['filter'])) {
			$filter = $this->_map[$param]['filter'];
		}

		switch ($filter) {
			case 'safehtml':
				$value = htmlspecialchars($value);
			break;
			case 'string':
				$value = htmlspecialchars(strip_tags($value));
			break;
		}

		return addslashes($value);
	}

	/**
	 * Public getter. Returns a config parameter,
	 * if it exists. In other case returns null.
	 * 
	 * @param string $params Dot-separated path to needly parameter
	 * @param string|array $except Determine parameters that will be excluded
	 * @param boolean $use_option If true, checking for existed self-titled option and return option value
	 * 
	 * @return mixed|null
	 */
	public function get($param, $except = null, $use_option = false) {
		$pieces = explode('.', $param);
		$piecesCount = count($pieces);

		$result = null;

		// Force result find. If we use options, find in database and return result if finded.
		// Please note, excerpts are not supported in options now.
		if($use_option) {
			$alias = $this->_helper->convertConfigPathToSetting($param); // @todo make dis shit dry {4}

			if(!is_null($result = get_option($alias, null))) {
				return $this->sanitizeOption($param, $result);
			}
		}

		// If not, find in config stack.
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

	public function get_current($param) {
		return $this->get($param, null, true);
	}

	public function get_default($param, $except = null) {
		return $this->get($param, $except, false);
	}
}