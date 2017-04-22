<?php

namespace TeaPageContent\App\Modules;

use TeaPageContent\App\Traits;
use TeaPageContent\App\Modules\Shortcode;

class Shortcode {
    use Traits\Predicates\Shortcode;

    private $ShortcodeBuilder = null;
    private $ShortcodeRenderer = null;

    public function __construct(Shortcode\Builder $ShortcodeBuilder, Shortcode\Renderer $ShortcodeRenderer) {
        $this->ShortcodeBuilder = $ShortcodeBuilder;
        $this->ShortcodeRenderer = $ShortcodeRenderer;
    }

    public function tea_page_content($user_attrs, $shortcode_content = null) {
        $template_params = [];

        $attrs = $this->ShortcodeBuilder->build_common_shortcode_attrs($user_attrs);

        if($this->is_enclosed_shortcode($shortcode_content)) {
            $this->ShortcodeBuilder->build_data_for_enclosed($attrs, $template_params, $shortcode_content);
        } else {
            $this->ShortcodeBuilder->build_data_for_single($attrs, $template_params);
        }

        $output = $this->ShortcodeRenderer->render_shortcode($attrs, $template_params);

        return $output;
    }
}