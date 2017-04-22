<?php

namespace TeaPageContent\App\PostTypes;

use TeaPageContent\App\Config;

class Repository {
    private $Config = null;

    public function __construct(Config\Repository $Config) {
        $this->Config = $Config;
    }

    public function get() {
        $types = $this->get_post_types();

        return $types;
    }

    private function get_post_types() {
        $args = $this->get_post_types_args();
        $operator = $this->get_post_types_operator();

        $types = get_post_types($args, 'names', $operator);
        $types = apply_filters('tpc_post_types', $types);
        
        return $types;
    }

    private function get_post_types_operator() {
        $operator = $this->Config->get('system.posts.types-operator');
        $operator = apply_filters('tpc_post_types_operator', $operator);

        return $operator;
    }

    private function get_post_types_args() {
        $args = $this->Config->get('defaults.post-types');
        $args = apply_filters('tpc_post_types_args', $args);

        return $args;
    }
}