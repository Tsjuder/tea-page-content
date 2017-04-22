<?php

namespace TeaPageContent\App\Traits\Predicates;

trait Common {
    private function is_array_and_elements_not_empty(Array $array) {
        return count($array) && array_filter($array);
    }

    private function is_part_exists_in($array, $index) {
        return is_array($array) && isset($array[$index]);
    }

    private function is_element_not_empty_array($element) {
        return is_array($element) && !empty($element);
    }
}