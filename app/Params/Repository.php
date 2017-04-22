<?php

namespace TeaPageContent\App\Params;

class Repository {
    private $Builder = null;

    public function __construct(Builder $Builder) {
        $this->Builder = $Builder;
    }

    public function get(Array $instance, $mode = 'group', $test = false) {
        $params = $this->Builder->build_params($instance, $mode);
        $params = apply_filters('tpc_get_params', $params);

        return $params;
    }
}