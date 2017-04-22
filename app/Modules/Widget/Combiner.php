<?php

namespace TeaPageContent\App\Modules\Widget;

use TeaPageContent\App\TemplateVariables;
use TeaPageContent\App\Config;

class Combiner {
    private $Config = null;
    private $TemplateVariablesRepository = null;
    
    public function __construct(Config\Repository $Config, TemplateVariables\Repository $TemplateVariablesRepository) {
        $this->Config = $Config;
        $this->TemplateVariablesRepository = $TemplateVariablesRepository;
    }

    public function merge_with_defaults($instance) {
        $defaults = $this->Config->get('defaults.widget', ['per-page']);

        if(!is_array($instance)) {
            $instance = [];
        }

        return array_merge($defaults, $instance);
    }

    public function combine_defaults_with_instance_and_template_variables(Array $instance_new) {
        $defaults = $this->Config->get('defaults.widget', ['per-page']);

        $template_variables = $this->TemplateVariablesRepository->get($instance_new['template']);

        $prepared_instance = $instance_new + $defaults + $template_variables;

        return $prepared_instance;
    }
}