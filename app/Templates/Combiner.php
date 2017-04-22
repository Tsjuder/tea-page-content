<?php

namespace TeaPageContent\App\Templates;

use TeaPageContent\App\Config;

/**
 * Combine default templates with passed, that readed from custom directories.
 */
class Combiner {
    private $Config = null;
    
    public function __construct(Config\Repository $Config) {
        $this->Config = $Config;
    }

    public function combine_custom_templates_with_defaults(Array $custom_templates) {
        $predefined_templates = $this->Config->get('system.predefined-templates');

        return array_merge($predefined_templates, $custom_templates);
    }
}