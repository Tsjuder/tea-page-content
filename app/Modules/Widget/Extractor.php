<?php

namespace TeaPageContent\App\Modules\Widget;

use TeaPageContent\App\Config;
use TeaPageContent\App\Params;
use TeaPageContent\App\TemplateVariables;
use TeaPageContent\App\PageVariables;
use TeaPageContent\App\Templates;

class Extractor {
    private $WidgetVariablesExtractor = null;
    private $WidgetParamsExtractor = null;
    private $WidgetTemplatesExtractor = null;

    public function __construct(
        TemplatesExtractor $WidgetTemplatesExtractor,
        VariablesExtractor $WidgetVariablesExtractor,
        ParamsExtractor $WidgetParamsExtractor
    ) {
        $this->WidgetParamsExtractor = $WidgetParamsExtractor;
        $this->WidgetVariablesExtractor = $WidgetVariablesExtractor;
        $this->WidgetTemplatesExtractor = $WidgetTemplatesExtractor;
    }

    public function get_actual_widget_data(Array $instance, Array $data) {
        $data['title'] = apply_filters('widget_title', $instance['title']);

        $params = $this->WidgetParamsExtractor->get_actual_params($instance, 'flatten');

        if(!empty($params['entries'])) {
            $data['markup'] = $this->WidgetTemplatesExtractor->get_widget_markup($instance, $params);
        }

        return $data;
    }

    public function get_page_variables(Array $instance) {
        return $this->WidgetVariablesExtractor->get_page_variables($instance);
    }

    public function get_template_variables(Array $instance) {
        return $this->WidgetVariablesExtractor->get_template_variables($instance);
    }

    public function get_partials(Array $params) {
        return $this->WidgetTemplatesExtractor->get_partials($params);
    }

    public function get_actual_template(Array $instance, $for = 'client') {
        return $this->WidgetTemplatesExtractor->get_actual_template($instance, $for);
    }

    public function get_actual_template_path(Array $instance, $for = 'client') {
        return $this->WidgetTemplatesExtractor->get_actual_template_path($instance, $for);
    }
}