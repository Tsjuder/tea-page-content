<?php

namespace TeaPageContent\App\TemplateVariables;

class Repository {
    private $Extractor = null;

    public function __construct(Extractor $Extractor) {
        $this->Extractor = $Extractor;
    }
    
    public function get($template) {
        $variables = $this->Extractor->extract_variables_from_template($template);
        $variables = apply_filters('tpc_get_template_variables', $variables);

        return $variables;
    }
}