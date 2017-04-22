<?php

namespace TeaPageContent\App\PageVariables;

use TeaPageContent\App\Traits;

/**
 * Decode page-level variables from urlencoded raw string.
 */
class Decoder {
    use Traits\Utils\Common;

    private $PageVariablesBuilder = null;

    public function __construct(Builder $PageVariablesBuilder) {
        $this->PageVariablesBuilder = $PageVariablesBuilder;
    }

    public function decode_page_variables($query_string, $entry_id = null, $apply_rules = true) {
        $query_string = trim($query_string);
        $query_string = apply_filters('tpc_page_variables_raw', $query_string, $entry_id);

        $query_string_pairs = $this->get_pairs_from_query_string($query_string);

        $page_variables = [];
        if($query_string && $query_string_pairs) {
            $page_variables = $this->PageVariablesBuilder->build_page_variables_from_query_pairs($query_string_pairs, $entry_id, $apply_rules);
        }

        $page_variables = apply_filters('tpc_page_variables', $page_variables, $entry_id);

        return $page_variables;
    }
}