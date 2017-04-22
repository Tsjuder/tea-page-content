<?php

namespace TeaPageContent\App\PageVariables;

use TeaPageContent\App\Traits;

/**
 * Build arrays with page-level variables
 * based on passed key-value pairs and entry ID.
 */
class Builder {
    use Traits\Predicates\PageVariables;

    private $Formatter = null;
    private $RulesActuator = null;

    public function __construct(Formatter $Formatter, RulesActuator $RulesActuator) {
        $this->Formatter = $Formatter;
        $this->RulesActuator = $RulesActuator;
    }

    public function build_page_variables_from_query_pairs(Array $query_string_pairs, $entry_id, $apply_rules) {
        $page_variables = [];

        foreach($query_string_pairs as $query_pair) {
            $page_variable = $this->build_page_variable_from_query_pair($query_pair);

            if($this->is_page_variable_correct($page_variable)) {
                if($apply_rules) {
                    $page_variable = $this->RulesActuator->apply_rules_to_parsed_variable($page_variable, $entry_id);
                }

                $page_variables[$page_variable['title']] = $page_variable['value'];
            }
        }

        return $page_variables;
    }

    private function build_page_variable_from_query_pair($query_pair) {
        $page_variable = array_map(function($query_pair) {
            return urldecode($query_pair);
        }, explode('=', $query_pair));

        $page_variable = $this->Formatter->remap_page_variable_keys($page_variable);

        return $page_variable;
    }
}