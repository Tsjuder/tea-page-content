<?php

namespace TeaPageContent\App\Traits\Utils;

trait Common {
    private function escape_value($value) {
        return addslashes($value);
    }

    private function get_pairs_from_query_string($query_string) {
        return explode('&', $query_string);
    }

    private function explode_by_dot($param) {
        return explode('.', $param);
    }

    private function explode_by_comma($string) {
        return explode(',', $string);
    }
}