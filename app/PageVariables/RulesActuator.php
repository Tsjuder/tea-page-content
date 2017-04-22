<?php

namespace TeaPageContent\App\PageVariables;

use TeaPageContent\App\Config;

/**
 * Stores itself morph rules for page-level variables, and applying it 
 * to passed page-level variables. Returns morphed (or non-changed) variables.
 */
class RulesActuator {
    private $Config = null;

    public function __construct(Config\Repository $Config) {
        $this->Config = $Config;
    }

    public function apply_rules_to_parsed_variable(Array $page_variable, $entry_id) {
        switch ($page_variable['title']) {
            case 'page_thumbnail':
                $page_variable['value'] = $this->apply_rule_to_page_thumbnail($page_variable['value'], $entry_id);
                break;
        }

        return $page_variable;
    }

    private function apply_rule_to_page_thumbnail($page_thumbnail, $entry_id) {
        if(is_numeric($page_thumbnail)) {
            if(is_admin()) {
                $page_thumbnail = wp_get_attachment_url($page_thumbnail);
            } else {
                $thumbnail_size = $this->Config->get('defaults.thumbnails.size');
                $thumbnail_size = apply_filters('tpc_thumbnail_size', $thumbnail_size, $page_thumbnail, $entry_id);

                $page_thumbnail = wp_get_attachment_image($page_thumbnail, $thumbnail_size);
            }
        }

        return $page_thumbnail;
    }
}