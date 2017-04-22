<?php

namespace TeaPageContent\App\Params;

use TeaPageContent\App\PageVariables;
use TeaPageContent\App\Config;

/**
 * Extract params from instance.
 */
class Extractor {
    private $Config = null;
    private $PageVariablesRepository = null;
    private $PageVariablesFormatter = null;

    public function __construct(
        Config\Repository $Config,
        PageVariables\Repository $PageVariablesRepository,
        PageVariables\Formatter $PageVariablesFormatter
    ) {
        $this->Config = $Config;
        $this->PageVariablesRepository = $PageVariablesRepository;
        $this->PageVariablesFormatter = $PageVariablesFormatter;
    }

    public function get_actual_post_ids(Array $instance) {
        $post__in = null;

        if(!empty($instance['posts'])) {
            $actual = $instance['posts'];

            if(is_serialized($actual)) {
                $post__in = unserialize($actual);
            } else {
                $post__in = $actual;
            }
        }

        return $post__in;
    }

    public function extract_template_variables_from(Array $instance) {
        $template_variables = [];

        if(isset($instance['template_variables'])) {
            $template_variables = $instance['template_variables'];
        }

        return $template_variables;
    }

    public function extract_page_variables_from(Array $instance, Array $entry) {
        $page_variables = $this->PageVariablesRepository->get(
            $instance['page_variables'],
            $entry['id']
        );

        // Then we can merge it with original entry.
        // By default, original values in entry will be OVERRIDE,
        // and if you want change this behavior,
        // you can use filter `tpc_get_page_variables`
        $entry = array_merge($entry, $this->PageVariablesFormatter->remove_page_variable_prefix_from($page_variables));
        
        return $entry;
    }

    public function get_actual_instance(Array $instance) {
        $instance = $instance + $this->Config->get('defaults.widget', ['per-page', 'caller']);

        return $instance;
    }
}