<?php

namespace TeaPageContent\App\Modules\Shortcode;

use TeaPageContent\App\PageVariables;

/**
 * Combine user attributes from shortcode with defaults,
 * also combine it with page- and template-level variables.
 */
class Combiner {
    public function __construct(PageVariables\Combiner $PageVariablesCombiner, PageVariables\Formatter $PageVariablesFormatter) {
        $this->PageVariablesCombiner = $PageVariablesCombiner;
        $this->PageVariablesFormatter = $PageVariablesFormatter;
    }

    public function combine_user_attrs_with_defaults(Array $user_attrs, Array $defaults) {
        $attrs = [];

        foreach ($defaults as $attr_title => $attr_value) {
            if($attr_title === 'caller') {
                $attrs[$attr_title] = $attr_value;

                continue;
            }

            if(isset($user_attrs[$attr_title])) {
                $attrs[$attr_title] = $attr_value;
            } else {
                $attrs[$attr_title] = null;
            }
        }

        $attrs = shortcode_atts($attrs, $user_attrs);

        return $attrs;
    }

    public function combine_template_variables_with_user_attrs(Array $template_variables, Array $user_attrs) {
        $combined = [];

        foreach ($template_variables as $variable => $value) {
            if($value['type'] === 'caption') continue;
            
            if(isset($user_attrs[$variable])) {
                $combined[$variable] = $user_attrs[$variable];
            } else {
                switch ($value['type']) {
                    case 'checkbox':
                        if(reset($value['defaults'])) {
                            $combined[$variable] = $variable;    
                        }
                    break;
                    
                    default:
                        $combined[$variable] = reset($value['defaults']);
                    break;
                }
            }
        }

        return $combined;
    }

    public function combine_entries_with_page_variables(Array $entries, Array $attrs) {
        foreach ($entries as &$entry) {
            $current_page_variables = $attrs['page_variables'][$entry['id']];

            $prepared_page_variables = $this->PageVariablesFormatter->remove_page_variable_prefix_from($current_page_variables);

            // Then we can merge it with original entry.
            // By default, original values in entry will be OVERRIDE,
            // and if you want change this behavior,
            // you can use filter `tpc_get_page_variables`
            $entry = array_merge($entry, $prepared_page_variables);
        }
        unset($entry);

        return $entries;
    }

    public function merge_entries_list_with(Array $shortcode_attrs, Array $entries_list) {
        $merged_entries = [];

        if(isset($shortcode_attrs['posts'])) {
            $current_posts = $shortcode_attrs['posts'];

            $merged_entries = array_merge($entries_list, $current_posts);
        }
        

        return $merged_entries;
    }

    public function merge_page_variables_with(Array $shortcode_attrs, Array $page_variables) {
        $merged_variables = [];
        $temp = [];

        if(isset($shortcode_attrs['posts'])) {
            $current_posts = $shortcode_attrs['posts'];

            foreach ($current_posts as $current_post_id) {
                $current_post_id = (int)trim($current_post_id);

                $temp[$current_post_id] = $this->PageVariablesCombiner->combine_page_variables_with_defaults($shortcode_attrs, $current_post_id);
            }

            $merged_variables = $page_variables + $temp;
        }

        return $merged_variables;
    }
}