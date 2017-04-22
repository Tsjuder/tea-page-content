<?php

namespace TeaPageContent\App\Modules\Widget;

use TeaPageContent\App\PageVariables;
use TeaPageContent\App\TemplateVariables;

/**
 * Extract page- and template-level variables from instance.
 */
class VariablesExtractor {
    private $WidgetTemplatesExtractor = null;
    private $PageVariablesRepository = null;
    private $TemplateVariablesRepository = null;

    public function __construct(
        TemplatesExtractor $WidgetTemplatesExtractor, 
        PageVariables\Repository $PageVariablesRepository, 
        TemplateVariables\Repository $TemplateVariablesRepository
    ) {
        $this->WidgetTemplatesExtractor = $WidgetTemplatesExtractor;
        $this->PageVariablesRepository = $PageVariablesRepository;
        $this->TemplateVariablesRepository = $TemplateVariablesRepository;
    }

    public function get_page_variables(Array $instance) {
        $page_variables = [];

        if(isset($instance['page_variables'])) {
            $page_variables = $this->PageVariablesRepository->get($instance['page_variables']);
        }

        return $page_variables;
    }

    public function get_template_variables(Array $instance) {
        $template_variables = [];

        $template = $this->WidgetTemplatesExtractor->get_actual_template($instance, 'client');

        if($template) {
            $template_variables = $this->TemplateVariablesRepository->get($template);
        }

        return $template_variables;
    }
}