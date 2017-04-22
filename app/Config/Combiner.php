<?php

namespace TeaPageContent\App\Config;

/**
 * Combine mapped settings with defaults.
 */
class Combiner {
    private $Mapper = null;
    private $ConfigRepository = null;

    public function __construct(Mapper $Mapper, Repository $ConfigRepository) {
        $this->Mapper = $Mapper;
        $this->ConfigRepository = $ConfigRepository;
    }

    public function combine_defaults_with_mapped() {
        $combined_settings = [];
        $mapped_settings = $this->Mapper->get_mapped_settings();

        foreach ($mapped_settings as $alias => &$data) {
            $mapped_settings[$alias]['default'] = $this->ConfigRepository->get_current($alias);
        }

        return $mapped_settings;
    }
}