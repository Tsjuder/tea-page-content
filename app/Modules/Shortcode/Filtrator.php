<?php

namespace TeaPageContent\App\Modules\Shortcode;

use TeaPageContent\App\Config;

/**
 * Filter every shortcode param and unset
 * params which are not in list in config.
 */
class Filtrator {
    private $Config = null;

    public function __construct(Config\Repository $Config) {
        $this->Config = $Config;
    }

    public function filter_inner_shortcode_attrs(Array $shortcode_attrs) {
        $defaults = $this->Config->get('defaults.shortcode');

        $inner_attrs = [];
        $attrs_count = count($shortcode_attrs);

        // Now combine title and value into one param
        for($i = 0; $i < $attrs_count; $i++) {
            $attr = $shortcode_attrs[$i];

            // Check for non-page-level variables, if it exists, skip two iterations
            if($attr !== 'posts' && in_array($attr, array_keys($defaults))) {
                $i++;

                continue;
            }

            // Combine only if current piece is really param...
            if($i < $attrs_count - 1 && preg_match('/^[^\[][\w\d]+/ui', $attr)) {
                $next_attr = $shortcode_attrs[$i + 1];

                if($attr === 'posts') {
                    $next_attr = explode(',', $next_attr);
                }

                $inner_attrs[$attr] = $next_attr;

                $i++;
            }
        }

        return $inner_attrs;
    }
}