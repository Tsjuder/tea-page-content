<?php

namespace TeaPageContent\App\Entries;

use TeaPageContent\App\Config;
use TeaPageContent\App\PostTypes;

/**
 * Generate and return params for entries.
 */
class Params {
    private $Config = null;
    private $EntryExtractor = null;
    private $PostTypesRepository = null;

    public function __construct(
        Config\Repository $Config, 
        Extractor $EntryExtractor, 
        PostTypes\Repository $PostTypesRepository
    ) {
        $this->Config = $Config;
        $this->EntryExtractor = $EntryExtractor;
        $this->PostTypesRepository = $PostTypesRepository;
    }

    public function get_post_query_params(Array $params) {
        // @important внимание! здесь может быть проблема, т.к. в таком режиме
        // все, что замапано настройками в ОПЦИИ, не будет подтянуто
        // @see get_current метод
        $default_query_params = $this->Config->get('defaults.posts');

        $query_params = [];
        foreach ($default_query_params as $param_name => $param_value) {
            if(isset($params[$param_name])) {
                $query_params[$param_name] = $params[$param_name];
            } else {
                $query_params[$param_name] = $param_value;
            }
        }

        $query_params = array_merge($query_params, [
            'post_type' => $this->PostTypesRepository->get(),
            'post__in' => $this->EntryExtractor->get_actual_post_in_param($params),
        ]);

        $query_params = apply_filters('tpc_post_query_params', $query_params);

        return $query_params;
    }
}