<?php

namespace TeaPageContent\App\Main;

use TeaPageContent\App\Config;
use TeaPageContent\App\Notices;
use TeaPageContent\App\TemplateVariables;
use TeaPageContent\App\Templates;

/**
 * Callbacks for AJAX actions
 */
class Callbacks {
    private $Config = null;
    private $ShortcodeGenerator = null;
    private $NoticeManager = null;
    private $TemplateVariablesRepository = null;
    private $Resolver = null;
    private $Renderer = null;

    public function __construct(
        Config\Repository $Config,
        ShortcodeGenerator $ShortcodeGenerator,
        Notices\Manager $NoticeManager,
        TemplateVariables\Repository $TemplateVariablesRepository,
        Templates\Resolver $Resolver,
        Templates\Renderer $Renderer
    ) {
        $this->Config = $Config;
        $this->ShortcodeGenerator = $ShortcodeGenerator;
        $this->NoticeManager = $NoticeManager;
        $this->TemplateVariablesRepository = $TemplateVariablesRepository;
        $this->Resolver = $Resolver;
        $this->Renderer = $Renderer;
    }

    public function generate_shortcode_callback() {
        $this->ShortcodeGenerator->generate();

        wp_die();
    }

    public function set_notice_seen_callback() {
        $this->NoticeManager->update_deprecated_notice_option();

        wp_die();
    }

    public function get_template_variables_callback() {
        if(isset($_POST['template']) && isset($_POST['mask'])) {
            $template = preg_replace('/[^-\w\d_]/', '', (string)$_POST['template']);
            $mask = preg_replace('/[^-\w\d_(\[\])]/', '', (string)$_POST['mask']);

            $layout_data = $this->Config->get('defaults.templates.admin.partials.template-variables');
            $layout_path = $this->Resolver->get_resolved_template_path_from($layout_data);
            
            $variables = $this->TemplateVariablesRepository->get($template);

            echo $this->Renderer->render($layout_path, array(
                'template_variables' => $variables,
                'mask' => $mask
            ));
        }

        wp_die();
    }
}