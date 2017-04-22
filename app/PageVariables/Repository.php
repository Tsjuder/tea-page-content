<?php

namespace TeaPageContent\App\PageVariables;

class Repository {
    private $Extractor = null;

    public function __construct(Extractor $Extractor) {
        $this->Extractor = $Extractor;
    }

    /**
     * @param array $page_variables
     * @param int|null $entry_id 
     * @return array
     */
    public function get(Array $raw_page_variables, $entry_id = null) {
        $page_variables = $this->Extractor->get_page_variables_from($raw_page_variables, $entry_id);
        $page_variables = apply_filters('tpc_get_page_variables', $page_variables);

        return $page_variables;
    }
}