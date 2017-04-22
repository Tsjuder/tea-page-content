<?php

namespace TeaPageContent\App\Config;

/**
 * Filtering input data by passed filter. 
 * ALso return filter for passed element.
 */
class Filtrator {
    public function get_filter_from($element) {
        $filter = 'none';

        if(isset($element['filter'])) {
            $filter = $element['filter'];
        }

        return $filter;
    }

    public function apply_filter_to($value, $filter) {
        $result = $value;

        switch ($filter) {
            case 'safehtml':
                $result = $this->filter_safehtml($value);
                break;
                
            case 'string':
                $result = $this->filter_string($value);
                break;
        }

        return $result;
    }

    private function filter_safehtml($value) {
        return htmlspecialchars($value);
    }

    private function filter_string($value) {
        return htmlspecialchars(strip_tags($value));
    }
}