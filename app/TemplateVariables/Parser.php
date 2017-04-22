<?php

namespace TeaPageContent\App\TemplateVariables;

use TeaPageContent\App\Traits;

/**
 * Parse raw header from template file to array with template-level variables.
 */
class Parser {
    use Traits\Predicates\TemplateVariables;

    private $regexp = '/(?=@|\*)(?:@|\*)\w*?\s|((?:"[^"]+")|(?:[\S]+))/i';

    private $Filtrator = null;

    public function __construct(Filtrator $Filtrator) {
        $this->Filtrator = $Filtrator;
    }

    public function parse_variables_from_stream($stream) {
        $variables = [];

        $is_header = false;

        while(($line = fgets($stream)) !== false) {
            if($this->is_header_starts_in_line($line)) {
                $is_header = true;
            } elseif($this->is_header_ends_in_line($line)) {
                break;
            }

            if($is_header && $this->is_variable_exists_in_line($line)) {
                $variable = $this->parse_line($line);

                if($variable && isset($variable['name'])) {
                    $variables[$variable['name']] = $variable;
                }
            }
        }

        return $variables;
    }

    private function parse_line($line) {
        $variable = $this->parse_variable_from_line($line);

        if($variable) {
            $variable = $this->Filtrator->filter_variable_elements_by_mask($variable);
        }

        $variable = apply_filters('tpc_get_template_variable', $variable);

        return $variable;
    }

    private function parse_variable_from_line($line) {
        $splitted_line = preg_split($this->regexp, $line, -1, PREG_SPLIT_DELIM_CAPTURE);
        $variable = preg_grep($this->regexp, $splitted_line);

        if(!empty($variable)) {
            // Just reindex it
            $variable = array_values($variable);

            return $variable;
        }
    }
}