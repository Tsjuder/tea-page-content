<?php

namespace TeaPageContent\App\PageVariables;

use TeaPageContent\App\Config;

/**
 * Combine page-level variable with passed data,
 * applying rules in process, if required.
 */
class Combiner {
    private $Config = null;
    private $Formatter = null;
    private $RulesActuator = null;

    public function __construct(
        Config\Repository $Config, 
        Formatter $Formatter, 
        RulesActuator $RulesActuator
    ) {
        $this->Config = $Config;
        $this->Formatter = $Formatter;
        $this->RulesActuator = $RulesActuator;
    }

    public function combine_page_variables_with_defaults(Array $attrs, $entry_id = null, $apply_rules = true) {
        $default_variables = $this->Config->get('defaults.page-variables');
        $extracted_variables = array_intersect_key($attrs, $default_variables);

        if($apply_rules) {
            $extracted_variables = $this->apply_rules_to_variables($extracted_variables, $entry_id);
        }

        return $extracted_variables;
    }

    private function apply_rules_to_variables(Array $extracted_variables, $entry_id) {
        foreach ($extracted_variables as $variable_title => $variable_value) {
            $page_variable = $this->Formatter->remap_page_variable_keys(array($variable_title, $variable_value));
            $page_variable = $this->RulesActuator->apply_rules_to_parsed_variable($page_variable, $entry_id);

            $extracted_variables[$page_variable['title']] = $page_variable['value'];
        }

        return $extracted_variables;
    }
}