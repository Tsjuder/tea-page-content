<?php

namespace TeaPageContent\App\Modules\Widget;

use TeaPageContent\App\PageVariables;
use TeaPageContent\App\Entries;
use TeaPageContent\App\Templates;

/**
 * Add to passed data (building instance) another data (old
 * instance or new instance, or arrays with variables of defaults).
 */
class Appender {
    private $Decoder = null;
    private $WidgetExtractor = null;
    private $TemplatesRepository = null;
    private $EntriesRepository = null;

    public function __construct(
        PageVariables\Decoder $Decoder, 
        Extractor $WidgetExtractor, 
        Templates\Repository $TemplatesRepository, 
        Entries\Repository $EntriesRepository
    ) {
        $this->Decoder = $Decoder;
        $this->WidgetExtractor = $WidgetExtractor;
        $this->TemplatesRepository = $TemplatesRepository;
        $this->EntriesRepository = $EntriesRepository;
    }

    public function add_posts_to_updated_instance(Array $instance, Array $instance_new, $param_name) {
        $instance[$param_name] = serialize($instance_new[$param_name]);

        return $instance;
    }

    public function add_param_to_updated_instance(Array $instance, $instance_new, $param_name) {
        if(isset($instance_new[$param_name])) {
            $instance[$param_name] = $instance_new[$param_name];
        } else {
            $instance[$param_name] = null;
        }

        return $instance;
    }

    public function add_template_level_variables_to_updated_instance(Array $instance, Array $instance_new, $param_name) {
        $instance = $this->add_template_variables_storage_if_not_exists($instance);

        if(isset($instance_new[$param_name])) {
            $instance['template_variables'][$param_name] = $instance_new[$param_name];
        } else {
            $instance['template_variables'][$param_name] = null;
        }

        return $instance;
    }

    public function add_page_level_variables_to_updated_instance(Array $instance, Array $instance_new, $param_name) {
        $instance = $this->add_page_variables_storage_if_not_exists($instance);

        foreach ($instance_new[$param_name] as $page_id => $variable_data) {
            if(!trim($variable_data)) { // If raw variable data is not empty...
                continue;
            }

            // try to parse it for check values of every variable...
            $parsed_data = $this->Decoder->decode_page_variables($variable_data, null, false);

            // ... and, if parsed data is not empty, save page vars
            if(!empty($parsed_data)) {
                $instance['page_variables'][$page_id] = $variable_data;
            } else { 
                // if empty, unset page vars
                unset($instance['page_variables'][$page_id]);
            }
        }

        return $instance;
    }

    public function add_partials_to(Array $params) {
        $params['partials'] = $this->WidgetExtractor->get_partials($params);

        return $params;
    }

    public function add_templates_related_props_to(Array $params) {
        if(!isset($params['instance'])) {
            return $params;
        }

        $params['templates'] = $this->TemplatesRepository->get();
        $params['template_variables'] = $this->WidgetExtractor->get_template_variables($params['instance']);

        return $params;
    }

    public function add_entries_related_props_to(Array $params) {
        $entries = $this->EntriesRepository->get();

        $params['entries'] = $entries;
        $params['page_variables'] = $this->WidgetExtractor->get_page_variables($params['instance']);

        return $params;
    }

    private function add_template_variables_storage_if_not_exists(Array $instance) {
        if(!isset($instance['template_variables'])) {
            $instance['template_variables'] = [];
        }

        return $instance;
    }

    private function add_page_variables_storage_if_not_exists(Array $instance) {
        if(!isset($instance['page_variables'])) {
            $instance['page_variables'] = [];
        }

        return $instance;
    }
}