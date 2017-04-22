<?php

namespace TeaPageContent\App\Traits\Predicates;

trait Config {
    private function is_last_piece_in_parsed_params($param_length, $i) {
        return $i === $param_length;
    }

    private function is_parsed_param_in_config($config, Array $parsed_param, $i) {
        return is_array($config) && isset($parsed_param[$i]) && isset($config[$parsed_param[$i]]);
    }

    private function is_map_have_passed_param($map, $param) {
        return is_array($map) && isset($map[$param]);
    }

    private function is_correct_setting_name($setting_name) {
        return strpos($setting_name, 'tpc_') !== false && !preg_match('/[^\w-]/', $setting_name);
    }

    private function is_correct_setting_value($setting_value) {
        return is_scalar($setting_value);
    }
}