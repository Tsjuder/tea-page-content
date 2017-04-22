<?php

namespace TeaPageContent\App\Templates;

class Repository {
    private $Extractor = null;

    public function __construct(Extractor $Extractor) {
        $this->Extractor = $Extractor;
    }

    public function get() {
        $templates = $this->Extractor->get_templates();
        $templates = apply_filters('tpc_get_templates', $templates);

        return $templates;
    }
}