<?php

namespace TeaPageContent\App\Config;

/**
 * Returns mapped settings.
 * Mapped settings are result of combine records from config map
 * and same records from config.
 * This class also can convert config path to map path.
 */
class Mapper {
    private $MapRepository = null;

    public function __construct(Map\Repository $MapRepository) {
        $this->MapRepository = $MapRepository;
    }

    public function get_mapped_settings() {
        $result = [];
        $map = $this->MapRepository->get_map();

        foreach ($map as $config_path => $params) {
            $alias = $this->convert_config_path_to_setting($config_path);

            $result[$alias] = $params;
        }

        return $result;
    }

    public function convert_setting_to_config_path($setting) {
        if(!is_string($setting)) {
            return false;
        }

        return str_replace(['tpc_', '__'], ['', '.'], $setting);
    }

    public function convert_config_path_to_setting($config_path) {
        if(!is_string($config_path)) {
            return false;
        }

        return 'tpc_' . str_replace('.', '__', $config_path);
    }
}