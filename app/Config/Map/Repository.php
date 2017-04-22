<?php

namespace TeaPageContent\App\Config\Map;

class Repository {
    public static $_map = null;

    public function __construct(Loader $Loader) {
        if(is_null(self::$_map)) {
            $Loader->load_in(self::$_map);
        }
    }

    public function get_map() {
        return self::$_map;
    }

    public function set_map($map) {
        self::$_map = $map;
    }
}