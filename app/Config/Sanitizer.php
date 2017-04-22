<?php

namespace TeaPageContent\App\Config;

use TeaPageContent\App\Traits;

/**
 * Sanitize paths to records in config.
 * This required before map it, because mapped
 * settings will be write to database.
 */
class Sanitizer {
    use Traits\Predicates\Config;
    use Traits\Utils\Common;

    private $MapRepository = null;
    private $Filtrator = null;

    public function __construct(Map\Repository $MapRepository, Filtrator $Filtrator) {
        $this->MapRepository = $MapRepository;
        $this->Filtrator = $Filtrator;
    }

    public function sanitize_option($param, $value) {
        $result = $value;

        $map = $this->MapRepository->get_map();
        
        if($this->is_map_have_passed_param($map, $param)) {
            $filter = $this->Filtrator->get_filter_from($map[$param]);
            $result = $this->Filtrator->apply_filter_to($value, $filter);
        }

        $result = $this->escape_value($result);

        return $result;
    }
}