<?php

namespace TeaPageContent\App\Directories;

use TeaPageContent\App\Config;

class Repository {
    private $Config = null;

    public function __construct(Config\Repository $Config) {
        $this->Config = $Config;
    }

    public function get() {
        $paths = [];
        $directories = $this->Config->get('system.template-directories');

        foreach ($directories as $dir_name => $dir_data) {
            $paths[$dir_name] = $dir_data['path'];
        }

        $paths = apply_filters('tpc_template_directories', $paths);

        return $paths;
    }
}