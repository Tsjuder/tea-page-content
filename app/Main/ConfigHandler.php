<?php

namespace TeaPageContent\App\Main;

use TeaPageContent\App\Config;

/**
 * This class is required for correct handle config
 * and config map via filters, because in Config Repository
 * that operation is impossible.
 */
class ConfigHandler {
    private $Config = null;
    private $Map = null;
    
    public function __construct(Config\Repository $Config, Config\Map\Repository $Map) {
        $this->Config = $Config;
        $this->Map = $Map;
    }

    public function handle() {
        $config = $this->Config->get_config();
        $map = $this->Map->get_map();

        if(is_array($config)) {
            $config = apply_filters('tpc_config_array', $config);
            
            $this->Config->set_config($config);
        }

        if(is_array($map)) {
            $map = apply_filters('tpc_config_map_array', $map);

            $this->Map->set_map($map);
        }  
    }
}