<?php

namespace TeaPageContent\App\TemplateVariables;

use TeaPageContent\App\Config;
use TeaPageContent\App\Templates;

/**
 * Extract template-level variables from template file, then
 * call Parser and return parsed data.
 */
class Extractor {
    private $Resolver = null;
    private $Parser = null;

    public function __construct(Templates\Resolver $Resolver, Parser $Parser) {
        $this->Resolver = $Resolver;
        $this->Parser = $Parser;
    }

    public function extract_variables_from_template($template) {
        if(!$template) {
            return null;
        }

        $variables = [];

        $template_path = $this->Resolver->resolve_path_to_client_layout($template);
        
        if($template_path && $handle = fopen($template_path, 'r')) {
            $variables = $this->Parser->parse_variables_from_stream($handle);

            fclose($handle);
        }

        return $variables;
    }
}