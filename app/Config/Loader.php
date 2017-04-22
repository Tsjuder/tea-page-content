<?php

namespace TeaPageContent\App\Config;

class Loader {
    public function load_in(&$storage) {
        $config = require_once(\TeaPageContent\PLUGIN_PATH . '/config.php');

        if(is_array($config)) {
            $storage = $config;
        }
    }
}