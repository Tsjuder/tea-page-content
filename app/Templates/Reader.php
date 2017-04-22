<?php

namespace TeaPageContent\App\Templates;

/**
 * Read and return templates from directories. Filtering it via Filtrator.
 */
class Reader {
    private $Filtrator = null;

    public function __construct(Filtrator $Filtrator) {
        $this->Filtrator = $Filtrator;
    }

    public function get_templates_from_dir(Array $directories) {
        $templates = array();

        foreach ($directories as $type => $dir) {
            if(!is_dir($dir)) {
                continue;
            }

            $filtered_templates = $this->Filtrator->filter_templates_from_dir($dir);

            $templates = array_merge($templates, $filtered_templates);
        }

        return $templates;
    }
}