<?php

namespace TeaPageContent\App\Modules\Widget;

use TeaPageContent\App\Templates\Resolver;
use TeaPageContent\App\Templates\Renderer;

/**
 * Build params for widget, based on actual instance.
 */
class ParamsBuilder {
    private $WidgetExtractor = null;
    private $WidgetAppender = null;
    private $Resolver = null;
    private $Renderer = null;

    public function __construct(
        Extractor $WidgetExtractor, 
        Appender $WidgetAppender, 
        Resolver $Resolver, 
        Renderer $Renderer
    ) {
        $this->WidgetExtractor = $WidgetExtractor;
        $this->WidgetAppender = $WidgetAppender;
        $this->Resolver = $Resolver;
        $this->Renderer = $Renderer;
    }

    public function build_params(Array $instance, \WP_Widget $bind) {
        $params = $this->build_minimal_params($instance, $bind);

        $params = $this->WidgetAppender->add_entries_related_props_to($params);
        $params = $this->WidgetAppender->add_templates_related_props_to($params);
        $params = $this->WidgetAppender->add_partials_to($params);

        return $params;
    }

    private function build_minimal_params(Array $instance, \WP_Widget $bind) {
        $params = [];
        
        $params['instance'] = $instance;
        $params['bind'] = $bind;

        return $params;
    }
}