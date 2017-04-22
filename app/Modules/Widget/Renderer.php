<?php

namespace TeaPageContent\App\Modules\Widget;

use TeaPageContent\App\Config;
use TeaPageContent\App\Templates;

class Renderer {
    private $Config = null;
    private $TemplatesRenderer = null;
    private $TemplatesResolver = null;

    public function __construct(
        Config\Repository $Config,
        Templates\Renderer $TemplatesRenderer,
        Templates\Resolver $TemplatesResolver
    ) {
        $this->Config = $Config;
        $this->TemplatesRenderer = $TemplatesRenderer;
        $this->TemplatesResolver = $TemplatesResolver;
    }

    public function render_widget($data) {
        $template_data = $this->Config->get('defaults.templates.client.widget');
        $template_path = $this->TemplatesResolver->get_resolved_template_path_from($template_data);

        $content = $this->TemplatesRenderer->render($template_path, [
            'widget' => $data
        ]);
        
        return $content;
    }
}