<?php

namespace TeaPageContent\App\Templates;

class Renderer {
    public function render($template, Array $params = []) {
        $content = $this->load_template_content($template, $params);
        $content = apply_filters('tpc_render_template', $content);

        return $content;
    }

    private function load_template_content($template, Array $params) {
        if(!$template) {
            return null;
        }

        ob_start();
        $this->set_query_vars_for_template($params);
        load_template($template, false);
        $content = ob_get_clean();

        return $content;
    }

    private function set_query_vars_for_template(Array $params) {
        foreach ($params as $title => $value) {
            set_query_var($title, $value);
        }
    }
}