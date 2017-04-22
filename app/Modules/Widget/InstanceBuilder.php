<?php

namespace TeaPageContent\App\Modules\Widget;

use TeaPageContent\App\TemplateVariables;
use TeaPageContent\App\Config;

/**
 * Build actual instance based on old and new instances.
 */
class InstanceBuilder {
    private $TemplateVariablesRepository = null;
    private $WidgetAppender = null;
    private $Config = null;

    public function __construct(
        TemplateVariables\Repository $TemplateVariablesRepository, 
        Appender $WidgetAppender,
        Config\Repository $Config
    ) {
        $this->TemplateVariablesRepository = $TemplateVariablesRepository;
        $this->WidgetAppender = $WidgetAppender;
        $this->Config = $Config;
    }

    public function build_updated_instance(Array $prepared_instance, $instance_new, $instance_old) {
        $instance = $instance_old;

        $defaults = $this->Config->get('defaults.widget', ['per-page']);

        if(!is_array($instance)) {
            $instance = [];
        }

        foreach ($prepared_instance as $param => $value) {
            if($param === 'posts') {
                $instance = $this->WidgetAppender->add_posts_to_updated_instance($instance, $instance_new, $param);
            } elseif(array_key_exists($param, $defaults)) {
                $instance = $this->WidgetAppender->add_param_to_updated_instance($instance, $instance_new, $param);
            } else {
                $instance = $this->build_template_and_page_level_variables($instance, $instance_new, $param);
            }
        }

        return $instance;
    }

    private function build_template_and_page_level_variables(Array $instance, $instance_new, $param) {
        $template_variables = $this->TemplateVariablesRepository->get($instance_new['template']);

        if(array_key_exists($param, $template_variables)) {

            if($template_variables[$param]['type'] !== 'caption') {
                $instance = $this->WidgetAppender->add_template_level_variables_to_updated_instance($instance, $instance_new, $param);
            }

        } elseif($param === 'page_variables') {

            $instance = $this->WidgetAppender->add_page_level_variables_to_updated_instance($instance, $instance_new, $param);

        }

        return $instance;
    }
}