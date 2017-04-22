<?php

namespace TeaPageContent\App\TemplateVariables;

use TeaPageContent\App\Traits;
use TeaPageContent\App\Config;

/**
 * Filter and parse values of variables by mask.
 */
class Filtrator {
    use Traits\Predicates\Common;
    use Traits\Predicates\TemplateVariables;
    use Traits\Utils\TemplateVariables;
    
    private $Config = null;

    public function __construct(Config\Repository $Config) {
        $this->Config = $Config;
    }

    public function filter_variable_elements_by_mask($variable) {
        $mask = $this->Config->get('system.template-variables.mask.structure');
        
        foreach ($mask as $index => $item) {
            $variable[$item] = $this->get_actual_variable_item_data($index, $item, $variable);
            $variable[$item] = $this->filter_variable_item($item, $variable);

            if($this->is_part_exists_in($variable, $index)) {
                unset($variable[$index]);
            }
        }

        return $variable;
    }

    private function get_actual_variable_item_data($index, $item, $variable) {
        $actual_item_data = null;

        $defaults = $this->Config->get('defaults.template-variables');

        $is_part_exists = $this->is_part_exists_in($variable, $index);

        if(!$is_part_exists && isset($defaults[$item])) {
            $actual_item_data = $defaults[$item];
        } elseif($is_part_exists) {
            $actual_item_data = $variable[$index];
        }

        return $actual_item_data;
    }

    private function filter_variable_item($item_name, $variable) {
        switch ($item_name) {
            case 'defaults':
                $variable[$item_name] = $this->filter_variable_defaults($item_name, $variable);

                break;
        }

        return $variable[$item_name];
    }

    private function filter_variable_defaults($item_name, $variable) {
        $variable_item = $variable[$item_name];

        if($this->is_variable_defaults_value_list($variable_item)) {
            $variable[$item_name] = $this->get_defaults_value_list($variable_item);
        } elseif($this->is_variable_defaults_int_range($variable_item)) {
            $variable[$item_name] = $this->get_defaults_int_range($variable_item);
        } else {
            $variable[$item_name] = array($variable_item);
        }

        return $variable[$item_name];
    }
}