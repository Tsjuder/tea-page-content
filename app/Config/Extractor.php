<?php

namespace TeaPageContent\App\Config;

use TeaPageContent\App\Traits;

/**
 * Extract parameter from config based on parsed path.
 */
class Extractor {
    use Traits\Predicates\Config;

    public function extract_param_from(Array $config, Array $parsed_param, $except) {
        $result = null;
            
        for ($i = 0, $param_length = count($parsed_param); $i <= $param_length; $i++) {
            if($this->is_parsed_param_in_config($config, $parsed_param, $i)) {
                
                $config = $config[$parsed_param[$i]];
                continue;
            
            } elseif($this->is_last_piece_in_parsed_params($param_length, $i)) {
                $result = $this->remove_excepts_from_stack($except, $config);
            }

            break;
        }

        return $result;
    }

    private function remove_excepts_from_stack($except, $stack) {
        if(is_array($except) && ($intersect = array_intersect($except, array_keys($stack)))) {

            foreach ($intersect as $key) {
                unset($stack[$key]);
            }
            
        } elseif(is_string($except) && array_key_exists($except, $stack)) {
            unset($stack[$except]);
        }
    
        return $stack;
    }
}