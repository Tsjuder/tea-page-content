<?php

namespace TeaPageContent\App\PageVariables;

use TeaPageContent\App\Traits;
use TeaPageContent\App\Config;

/**
 * Extract page-level variables from raw original data.
 */
class Extractor {
    use Traits\Predicates\Common;
    use Traits\Predicates\PageVariables;

    private $Decoder = null;

    public function __construct(Decoder $Decoder) {
        $this->Decoder = $Decoder;
    }

    public function get_page_variables_from(Array $raw_variables, $entry_id) {
        $result = [];

        if($this->is_element_not_empty_array($raw_variables)) {
            if($this->is_variable_for_entry_exists($raw_variables, $entry_id)) {

                $result = $this->get_page_variables_for_entry($raw_variables, $entry_id);

            } elseif(is_null($entry_id)) {

                $result = $this->get_all_page_variables($raw_variables);

            }
        }

        return $result;
    }

    private function get_page_variables_for_entry(Array $raw_variables, $entry_id) {
        $query_string = $raw_variables[$entry_id];

        $decoded_variable = $this->Decoder->decode_page_variables($query_string, $entry_id);

        return $decoded_variable;
    }

    private function get_all_page_variables(Array $raw_variables) {
        $page_variables = [];

        foreach ($raw_variables as $entry_id => $query_string) {
            $decoded_variable = $this->Decoder->decode_page_variables($query_string, $entry_id);
            
            if($decoded_variable) {
                $page_variables[$entry_id] = $decoded_variable;
            }
        }

        return $page_variables;
    }
}