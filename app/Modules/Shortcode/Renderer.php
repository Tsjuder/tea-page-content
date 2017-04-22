<?php

namespace TeaPageContent\App\Modules\Shortcode;

use TeaPageContent\App\Templates;

class Renderer {
    private $TemplatesResolver = null;
    private $TemplatesRenderer = null;
    
    public function __construct(Templates\Resolver $TemplatesResolver, Templates\Renderer $TemplatesRenderer) {
        $this->TemplatesResolver = $TemplatesResolver;
        $this->TemplatesRenderer = $TemplatesRenderer;
    }

    public function render_shortcode(Array $attrs, Array $template_params) {
        $output = false;

        if(!empty($attrs) && !empty($template_params['entries'])) {
            $template_path = $this->TemplatesResolver->resolve_path_to_client_layout($attrs['template']);

            $output = $this->TemplatesRenderer->render($template_path, $template_params);
        }

        return $output;
    }
}