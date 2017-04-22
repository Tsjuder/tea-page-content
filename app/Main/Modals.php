<?php

namespace TeaPageContent\App\Main;

use TeaPageContent\App\Config;
use TeaPageContent\App\Templates;
use TeaPageContent\App\Entries;
use TeaPageContent\App\Params;
use TeaPageContent\App\TemplateVariables;

class Modals {
    private $Config = null;
    private $Resolver = null;
    private $Renderer = null;
    private $EntriesRepository = null;
    private $TemplatesRepository = null;
    private $TemplateVariablesRepository = null;

    public function __construct(
        Config\Repository $Config, 
        Templates\Resolver $Resolver, 
        Templates\Renderer $Renderer, 
        Entries\Repository $EntriesRepository, 
        Templates\Repository $TemplatesRepository, 
        TemplateVariables\Repository $TemplateVariablesRepository
    ) {
        $this->Config = $Config;
        $this->Resolver = $Resolver;
        $this->Renderer = $Renderer;
        $this->EntriesRepository = $EntriesRepository;
        $this->TemplatesRepository = $TemplatesRepository;
        $this->TemplateVariablesRepository = $TemplateVariablesRepository;
    }

    public function add_page_variables_modal() {
        $params = array(
            'page_variables' => $this->Config->get('defaults.page-variables')
        );

        $template_data = $this->Config->get('defaults.templates.admin.modals.page-variables');
        $template_path = $this->Resolver->get_resolved_template_path_from($template_data);

        if($template_path) {
            $content = $this->Renderer->render($template_path, $params);

            echo $content;
        }
    }

    public function add_insert_shortcode_modal() {
        $params = [
            'instance' => $this->Config->get('defaults.shortcode', 'caller'),
            'entries' => $this->EntriesRepository->get(),
            'templates' => $this->TemplatesRepository->get(),
            'template_variables' => $this->TemplateVariablesRepository->get('default'),
            'page_variables' => [],
            'partials' => [],
            'mask' => $this->Config->get('system.template-variables.mask.placeholder'),
        ];

        // Build up partials. Partials - small layouts of widget form,
        // that can be loaded or overriden dynamically, f.e. by ajax.
        // At this moment only one partial can be used.
        $layout_data = $this->Config->get('defaults.templates.admin.partials.template-variables');
        $layout_path = $this->Resolver->get_resolved_template_path_from($layout_data);

        $params['partials']['template_variables'] = $this->Renderer->render($layout_path, $params);
        $params = apply_filters('tpc_get_admin_params', $params);

        $template_data = $this->Config->get('defaults.templates.admin.modals.insert-shortcode');
        $template_path = $this->Resolver->get_resolved_template_path_from($template_data);

        $content = $this->Renderer->render($template_path, $params);

        echo $content;
    }
}