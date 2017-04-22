<?php

namespace TeaPageContent\App\Templates;

/**
 * Filter template files from all other stuff in folders.
 */
class Filtrator {
    public function filter_templates_from_dir($directory) {
        $templates = array_filter(scandir($directory), [$this, 'filter_template_by_filename']);

        return $templates;
    }

    private function filter_template_by_filename(&$filename) {
        if($this->is_template($filename)) {
            $filename = $this->crop_template_extension($filename);
            
            return true;
        }

        return false;
    }

    private function is_template($filename) {
        if(!is_dir($filename) && substr($filename, 0, 4) === 'tpc-') {
            return true;
        }

        return false;
    }

    private function crop_template_extension($template) {
        $extensions = array('.php');

        $template = str_replace($extensions, '', $template);

        return $template;
    }
}