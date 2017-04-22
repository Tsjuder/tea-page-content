<?php

namespace TeaPageContent\App\Main;

use TeaPageContent\App\Traits;

use TeaPageContent\App\Config;
use TeaPageContent\App\Templates;

class Settings {
    use Traits\Predicates\Config;

    private $Config = null;
    private $ConfigCombiner = null;
    private $ConfigMapper = null;
    private $Resolver = null;
    private $Renderer = null;

    public function __construct(
        Config\Repository $Config, 
        Config\Combiner $ConfigCombiner, 
        Config\Mapper $ConfigMapper, 
        Templates\Resolver $Resolver, 
        Templates\Renderer $Renderer
    ) {
        $this->Config = $Config;
        $this->ConfigCombiner = $ConfigCombiner;
        $this->ConfigMapper = $ConfigMapper;
        $this->Resolver = $Resolver;
        $this->Renderer = $Renderer;
    }

    public function render_page() {
        $params = array(
            'settings' => $this->ConfigCombiner->combine_defaults_with_mapped(),
        );

        $template_data = $this->Config->get('defaults.templates.admin.pages.settings');
        $template_path = $this->Resolver->get_resolved_template_path_from($template_data);

        echo $this->Renderer->render($template_path, $params);
    }

    /**
     * Update settings if POST is not empty
     */
    public function update() {
        if(!is_admin() || empty($_POST) || !isset($_POST['tpc_settings_update'])) {
            return;
        }
        
        foreach ($_POST as $setting_name => $setting_value) {
            if(!$this->is_correct_setting_name($setting_name) || !$this->is_correct_setting_value($setting_value)) {
                continue;
            }

            $config_path = $this->ConfigMapper->convert_setting_to_config_path($setting_name);

            $initial = $this->Config->get_default($config_path);

            if(is_null(get_option($setting_name, null))) {
                add_option($setting_name, $setting_value, '', 'no');
            } elseif($initial == $setting_value) {
                delete_option($setting_name);
            } else {
                update_option($setting_name, $setting_value, 'no');
            }
        }
    }
}