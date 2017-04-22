<?php

namespace TeaPageContent\App\Traits\Predicates;

trait Shortcode {
    private function is_enclosed_shortcode($shortcode_content) {
        return !is_null($shortcode_content) && trim($shortcode_content);
    }
}