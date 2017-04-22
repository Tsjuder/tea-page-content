<?php

namespace TeaPageContent\App\Modules\Shortcode;

use TeaPageContent\App\TemplateVariables;
use TeaPageContent\App\Entries;
use TeaPageContent\App\Params;

/**
 * Gets inner shortcode from main enclosed shortcode,
 * extract params and entries by shortcode attributes.
 */
class Extractor {
    public function __construct(TemplateVariables\Repository $TemplateVariablesRepository, Entries\Repository $EntriesRepository, Params\Repository $ParamsRepository) {
        $this->TemplateVariablesRepository = $TemplateVariablesRepository;
        $this->EntriesRepository = $EntriesRepository;
        $this->ParamsRepository = $ParamsRepository;
    }

    public function get_inner_shortcodes_from($shortcode_content) {
        $inner_shortcodes = [];
        
        if($shortcode_content) {
            // Split content data by shortcodes
            $inner_shortcodes = preg_split('/(\[tea_page_content.*\])/i', $shortcode_content, null, PREG_SPLIT_DELIM_CAPTURE);

            // And filter it for excluding trash data
            $inner_shortcodes = array_filter($inner_shortcodes, function($elem) {
                if(preg_match('/^\[tea_page_content/i', $elem)) {
                    return true;
                }

                return false;
            });
        }
        
        return $inner_shortcodes;
    }

    public function get_common_params_by_attrs(Array $attrs, $mode) {
        return $this->ParamsRepository->get($attrs, $mode);
    }

    public function get_entries_by_attrs(Array $attrs, $mode) {
        $entries =  $this->EntriesRepository->get(array(
            'post__in' => $attrs['post__in'],
            'order' => $attrs['order'],
            'orderby' => $attrs['orderby'],
        ), $mode, true);

        return $entries;
    }

    public function get_template_variables_for($template) {
        return $this->TemplateVariablesRepository->get($template);
    }

    public function get_shortcode_atts_from($shortcode) {
        return preg_split('/(\w+)=\"([^"]+)\"/ui', $shortcode, null, PREG_SPLIT_DELIM_CAPTURE);
    }
}