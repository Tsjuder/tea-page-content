<?php

namespace TeaPageContent\App\Config;

use TeaPageContent\App\Traits;

/**
 * Main config repository. Returns records from config,
 * or from database (if record is mapped).
 */
class Repository {
    use Traits\Utils\Common;
    
    private static $_config = null;

    private $Mapper = null;
    private $Sanitizer = null;
    private $Extractor = null;

    public function __construct (
        Loader $Loader,
        Mapper $Mapper,
        Sanitizer $Sanitizer,
        Extractor $Extractor
    ) 
    {
        $this->Mapper = $Mapper;
        $this->Sanitizer = $Sanitizer;
        $this->Extractor = $Extractor;

        if(is_null(self::$_config)) {
            $Loader->load_in(self::$_config);
        }
    }

    public function get_config() {
        return self::$_config;
    }

    public function set_config($config) {
        self::$_config = $config;
    }

    /**
     * @param string $params Dot-separated path to needly parameter
     * @param string|array $except Determine parameters that will be excluded
     * 
     * @return mixed|null
     */
    public function get($param, $except = null) {
        $parsed_param = $this->explode_by_dot($param);

        $config = $this->get_config();
        $result = $this->Extractor->extract_param_from($config, $parsed_param, $except);

        return $result;
    }

    /**
     * Get param from database, if it is possible.
     * If not, call `get` method.
     * 
     * Excepts not supported here.
     * 
     * @param string $param 
     * @return mixed|null
     */
    public function get_current($param) {
        $config_path = $this->Mapper->convert_setting_to_config_path($param);

        $result = get_option($param, null);
        if(!is_null($result)) {
            return $this->Sanitizer->sanitize_option($param, $result);
        }

        return $this->get($config_path, null);
    }

    /**
     * Get param from file. Wrapper for `get` method.
     * 
     * @param string $param 
     * @param mixed|null $except 
     * @return mixed|null
     */
    public function get_default($param, $except = null) {
        return $this->get($param, $except);
    }
}