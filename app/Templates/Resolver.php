<?php

namespace TeaPageContent\App\Templates;

use TeaPageContent\App\Config;

/**
 * Resolve passed template name (or template data array) to template path
 */
class Resolver {
    private $Config = null;

    public function __construct(Config\Repository $Config) {
        $this->Config = $Config;
    }

    public function get_resolved_template_path_from($template_data) {
        $template_name = $template_data['name'];
        
        $template_path = $template_data['path'];
        $template_path = $this->resolve_path_with_placeholders($template_path);
        $template_path = $template_path . $template_name . '.php';

        $template_path = apply_filters('tpc_get_template_path', $template_path);

        return $template_path;
    }

    public function resolve_path_to_client_layout($template) {
        $template_path = $this->get_template_path_if_exists($template);
        $template_path = apply_filters('tpc_get_template_path', $template_path);

        return $template_path;
    }

    private function resolve_path_with_placeholders($path, $is_replace = true) {
        $placeholders = $this->get_dir_placeholders();

        foreach ($placeholders as $item_title => $placeholder_data) {
            $placeholder = $placeholder_data['placeholder'];

            $replacement = '';
            if($is_replace) {
                $replacement = $placeholder_data['path'];
            }

            $path = preg_replace('/' . $placeholder . '/', $replacement, $path);
        }

        return ltrim($path, '/');
    }

    private function get_dir_placeholders() {
        $placeholders = [];
        $items = $this->Config->get('system.template-directories');

        foreach ($items as $item_key => $item_value) {
            if(is_array($item_value) && isset($item_value['placeholder']) && isset($item_value['path'])) {
                $placeholders[$item_key] = $item_value;
            }
        }

        return $placeholders;
    }

    private function get_template_path_if_exists($template) {
        if(!$template) {
            return null;
        }

        $template_path = locate_template('templates/' . $template . '.php');

        if(!$template_path) {
            $template_data = $this->Config->get('system.template-directories.layouts');
            $template_path = $template_data['path'] . '/' . $template . '.php';
        }

        return $template_path;
    }
}