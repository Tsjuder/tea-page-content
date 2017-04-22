<?php

namespace TeaPageContent\App\Modules\Widget;

use TeaPageContent\App\Config;
use TeaPageContent\App\Templates;

/**
 * Extract templates and partials from instance, and extract widget markup.
 */
class TemplatesExtractor {
    private $Config = null;
    private $Renderer = null;
    private $Resolver = null;

    public function __construct(Config\Repository $Config, Templates\Renderer $Renderer, Templates\Resolver $Resolver) {
        $this->Config = $Config;
        $this->Renderer = $Renderer;
        $this->Resolver = $Resolver;
    }

    public function get_widget_markup(Array $instance, Array $params) {
        $template_name = $this->get_actual_widget_template($instance);
        $template_path = $this->Resolver->resolve_path_to_client_layout($template_name);

        $markup = $this->Renderer->render($template_path, $params);

        return $markup;
    }

    public function get_partials(Array $params) {
        $partials = [];

        $layout_data = $this->Config->get('defaults.templates.admin.partials.template-variables');
        $layout_path = $this->Resolver->get_resolved_template_path_from($layout_data);

        $partials['template_variables'] = $this->Renderer->render($layout_path, $params);

        return $partials;
    }

    public function get_actual_template(Array $instance, $for = 'client') {
        $template = null;

        switch ($for) {
            case 'admin':
                $template = $this->get_actual_form_template();
                break;

            case 'client':
            default:
                $template = $this->get_actual_widget_template($instance);
                break;
        }

        return $template;
    }

    public function get_actual_template_path(Array $instance, $for = 'client') {
        $template_path = null;
        $template = $this->get_actual_template($instance, $for);

        switch ($for) {
            case 'admin':
                $template_data = $this->Config->get('defaults.templates.admin.modules.widget');
                break;

            case 'client':
            default:
                $template_data = $this->Config->get('defaults.templates.client.widget');
                break;
        }

        $template_path = $this->Resolver->get_resolved_template_path_from($template_data);
        
        return $template_path;
    }

    private function get_actual_widget_template(Array $instance) {
        $template = $instance['template'] ? $instance['template'] : null;
        $template = apply_filters('tpc_template_name', $template);

        return $template;
    }

    private function get_actual_form_template() {
        $template_data = $this->Config->get('defaults.templates.admin.modules.widget');

        $template = $template_data['name'];
        $template = apply_filters('tpc_admin_template_name', $template);

        return $template;
    }
}