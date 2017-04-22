<?php

namespace TeaPageContent\App\Traits\Predicates;

trait PageVariables {
    private function is_page_variable_correct($page_variable) {
        return $page_variable['title'] && isset($page_variable['value']) && trim($page_variable['value']);
    }

    private function is_variable_for_entry_exists(Array $raw_variables, $entry_id) {
        return !is_null($entry_id) && isset($raw_variables[$entry_id]);
    }
}