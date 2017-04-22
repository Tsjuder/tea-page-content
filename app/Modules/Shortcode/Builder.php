<?php

namespace TeaPageContent\App\Modules\Shortcode;

use TeaPageContent\App\Config;

/**
 * Create params and requred data objects for shortcode
 */
class Builder {
    private $Config = null;
    private $ShortcodeExtractor = null;
    private $ShortcodeFiltrator = null;
    private $ShortcodeCombiner = null;

    public function __construct(Config\Repository $Config, Extractor $ShortcodeExtractor, Filtrator $ShortcodeFiltrator, Combiner $ShortcodeCombiner) {
        $this->Config = $Config;
        $this->ShortcodeExtractor = $ShortcodeExtractor;
        $this->ShortcodeFiltrator = $ShortcodeFiltrator;
        $this->ShortcodeCombiner = $ShortcodeCombiner;
    }

    public function build_common_shortcode_attrs(Array $user_attrs) {
        $defaults = $this->Config->get('defaults.shortcode');
        $attrs = $this->ShortcodeCombiner->combine_user_attrs_with_defaults($user_attrs, $defaults);

        $template_variables = $this->ShortcodeExtractor->get_template_variables_for($attrs['template']);

        $attrs['template_variables'] = $this->ShortcodeCombiner->combine_template_variables_with_user_attrs($template_variables, $user_attrs);

        return $attrs;
    }

    public function build_data_for_enclosed(Array &$attrs, Array &$template_params, $shortcode_content) {
        $attrs['page_variables'] = [];
        $attrs['post__in'] = [];

        $inner_attrs = [];

        $inner_shortcodes = $this->ShortcodeExtractor->get_inner_shortcodes_from($shortcode_content);

        foreach ($inner_shortcodes as $index => $shortcode) {
            $shortcode_attrs = $this->ShortcodeExtractor->get_shortcode_atts_from($shortcode);

            $inner_attrs[$index] = $this->ShortcodeFiltrator->filter_inner_shortcode_attrs($shortcode_attrs);

            $attrs['page_variables'] = $this->ShortcodeCombiner->merge_page_variables_with($inner_attrs[$index], $attrs['page_variables']);

            $attrs['post__in'] = $this->ShortcodeCombiner->merge_entries_list_with($inner_attrs[$index], $attrs['post__in']);
        }

        $template_params = $this->build_template_params_for_enclosed($attrs);
    }

    public function build_data_for_single(&$attrs, &$template_params) {
        $template_params = $this->build_template_params_for_single($attrs);
    }

    private function build_template_params_for_enclosed(Array $attrs) {
        $template_params = [];

        $common_params = $this->ShortcodeExtractor->get_common_params_by_attrs($attrs, 'flatten');

        $template_params['entries'] = $this->ShortcodeExtractor->get_entries_by_attrs($attrs, 'flatten');

        $template_params['entries'] = $this->ShortcodeCombiner->combine_entries_with_page_variables($template_params['entries'], $attrs);

        $template_params['count'] = count($template_params['entries']);
        $template_params['template_variables'] = $attrs['template_variables'];

        $template_params = array_merge($template_params, $common_params);

        return $template_params;
    }

    private function build_template_params_for_single(Array $attrs) {
        return $this->ShortcodeExtractor->get_common_params_by_attrs($attrs, 'flatten');
    }
}