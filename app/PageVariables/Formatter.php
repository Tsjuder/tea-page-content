<?php

namespace TeaPageContent\App\PageVariables;

use TeaPageContent\App\Config;

/**
 * Format page-level variables, remove prefix.
 */
class Formatter {
    private $Config = null;

    public function __construct(Config\Repository $Config) {
        $this->Config = $Config;
    }

    public function remap_page_variable_keys(Array $page_variable) {
        return array(
            'title' => isset($page_variable[0]) ? trim($page_variable[0]) : null,
            'value' => isset($page_variable[1]) ? trim($page_variable[1]) : null,
        );
    }
    
    public function remove_page_variable_prefix_from(Array $page_variables) {
        $page_var_prefix = $this->Config->get('system.page-variables.prefix');

        $prepared_variables = array();
        foreach ($page_variables as $variable => $value) {
            $unprefixed_title = str_replace($page_var_prefix, '', $variable);

            $prepared_variables[$unprefixed_title] = $value;
        }

        return $prepared_variables;
    }
}