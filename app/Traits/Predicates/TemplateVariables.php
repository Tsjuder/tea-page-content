<?php

namespace TeaPageContent\App\Traits\Predicates;

trait TemplateVariables {
    private function is_header_starts_in_line($line) {
        return (strpos($line, '/**') !== false);
    }

    private function is_header_ends_in_line($line) {
        return (strpos($line, '*/') !== false);
    }

    private function is_variable_exists_in_line($line) {
        return (strpos($line, '@param') !== false);
    }

    private function is_variable_defaults_value_list($defaults) {
        return preg_match('/.\|./', $defaults);
    }

    private function is_variable_defaults_int_range($defaults) {
        return preg_match('/^(\d-|-\d)/', $defaults);
    }
}