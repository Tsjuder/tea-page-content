<?php

namespace TeaPageContent\App\Modules;

use TeaPageContent\App\DependencyInjector;

class Widget extends \WP_Widget {
    public function __construct() {
        parent::__construct(
            'tea-page-content', 
            __('Tea Page Content', 'tea-page-content'),
            [
                'description' => __('Allows display any content of any page or post.', 'tea-page-content')
            ],
            [
                'width' => 480
            ]
        );

        $DependencyInjector = new DependencyInjector;
        $DependencyInjector->inject_dependencies_to($this);
    }

    public function widget($args, $instance) {
        if(!empty($args)) {
            $data = $this->WidgetExtractor->get_actual_widget_data($instance, $args);

            if(isset($data['markup'])) {
                echo $this->WidgetRenderer->render_widget($data);
            }
        }
    }

    public function form($instance) {
        $instance = $this->WidgetCombiner->merge_with_defaults($instance);

        $params = $this->WidgetParamsBuilder->build_params($instance, $this);
        $params = apply_filters('tpc_get_admin_params', $params);

        // Render form
        $template_path = $this->WidgetExtractor->get_actual_template_path($instance, 'admin');
        $output = $this->TemplatesRenderer->render($template_path, $params);

        echo $output;
    }

    public function update($instance_new, $instance_old) {
        $prepared_instance = $this->WidgetCombiner->combine_defaults_with_instance_and_template_variables($instance_new);

        $instance = $this->WidgetInstanceBuilder->build_updated_instance($prepared_instance, $instance_new, $instance_old);

        return $instance;
    }
}