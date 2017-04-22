<?php

namespace TeaPageContent\App\Params;

use TeaPageContent\App\Entries;

/**
 * Build params array based on instance and building mode.
 */
class Builder {
    private $EntriesRepository = null;
    private $ParamsExtractor = null;

    public function __construct(Entries\Repository $EntriesRepository, Extractor $ParamsExtractor) {
        $this->EntriesRepository = $EntriesRepository;
        $this->ParamsExtractor = $ParamsExtractor;
    }

    public function build_params(Array $instance, $mode) {
        $params = [];

        $instance['post__in'] = $this->ParamsExtractor->get_actual_post_ids($instance);

        if($instance['post__in']) {
            $params = array_merge($params, $this->build_entry_related_params($instance, $mode));
            $params = array_merge($params, $this->build_variables_related_params($instance, $params));
        }

        $params = array_merge($params, $this->build_instance_related_params($instance));

        return $params;
    }

    private function build_entry_related_params(Array $instance, $mode) {
        $params = [];
        
        $params['entries'] = $this->EntriesRepository->get($instance, $mode);
        $params['count'] = count($params['entries']);

        return $params;
    }

    private function build_variables_related_params(Array $instance, Array $params) {
        $params['template_variables'] = $this->ParamsExtractor->extract_template_variables_from($instance);

        // Pass page variables from instance to params
        if(isset($instance['page_variables'])) {
            foreach ($params['entries'] as &$entry) {
                $entry = $this->ParamsExtractor->extract_page_variables_from($instance, $entry);
            }

            unset($instance['page_variables']);
            unset($entry);
        }

        return $params;
    }

    private function build_instance_related_params(Array $instance) {
        $params = [
            'instance' => $this->ParamsExtractor->get_actual_instance($instance),
        ];

        if(isset($params['instance']['caller'])) {
            $params['caller'] = $params['instance']['caller'];

            unset($params['instance']['caller']);
        }

        return $params;
    }
}