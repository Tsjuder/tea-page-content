<?php

namespace TeaPageContent\App\Config\Map;

class Loader {
    public function load_in(&$storage) {
        $map = require_once(\TeaPageContent\PLUGIN_PATH . '/maps/config.map.php');

        if(is_array($map)) {
            $storage = $map;
        }
    }
}