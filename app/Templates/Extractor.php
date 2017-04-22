<?php

namespace TeaPageContent\App\Templates;

use TeaPageContent\App\Directories;

/**
 * Extract templates from repository and combine it via Combiner.
 */
class Extractor {
    private $DirectoriesRepository = null;
    private $Reader = null;
    private $Combiner = null;
    
    public function __construct(
        Directories\Repository $DirectoriesRepository,
        Reader $Reader, 
        Combiner $Combiner
    ) {
        $this->DirectoriesRepository = $DirectoriesRepository;
        $this->Reader = $Reader;
        $this->Combiner = $Combiner;
    }

    public function get_templates() {
        $directories = $this->DirectoriesRepository->get();
        $custom_templates = $this->Reader->get_templates_from_dir($directories);

        $templates = $this->Combiner->combine_custom_templates_with_defaults($custom_templates);

        return $templates;
    }
}