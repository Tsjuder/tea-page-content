<?php

namespace TeaPageContent\App\Modules\Widget;

use TeaPageContent\App\Config;
use TeaPageContent\App\Params;

/**
 * Extract actual params from instance by mode.
 */
class ParamsExtractor {
    private $Config = null;
    private $ParamsRepository = null;

    public function __construct(
        Config\Repository $Config,
        Params\Repository $ParamsRepository
    ) {
        $this->Config = $Config;
        $this->ParamsRepository = $ParamsRepository;
    }

    public function get_actual_params(Array $instance, $mode) {
        $defaults = $this->Config->get('defaults.widget', ['per-page']);

        $params = $this->ParamsRepository->get($instance, $mode, true);
        $params['caller'] = $defaults['caller'];

        return $params;
    }
}