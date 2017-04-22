<?php

namespace TeaPageContent\App\Traits\Utils;

trait TemplateVariables {
    private function get_defaults_value_list($defaults) {
        return explode('|', $defaults);
    }

    private function get_defaults_int_range($defaults) {
        return explode('-', $defaults);
    }
}