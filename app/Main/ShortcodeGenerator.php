<?php

namespace TeaPageContent\App\Main;

use TeaPageContent\App\Config;
use TeaPageContent\App\TemplateVariables;
use TeaPageContent\App\PageVariables;

class ShortcodeGenerator {
    private $Config = null;
    private $TemplateVariablesRepository = null;
    private $PageVariablesDecoder = null;

    public function __construct(Config\Repository $Config, TemplateVariables\Repository $TemplateVariablesRepository, PageVariables\Decoder $PageVariablesDecoder) {
        $this->Config = $Config;
        $this->TemplateVariablesRepository = $TemplateVariablesRepository;
        $this->PageVariablesDecoder = $PageVariablesDecoder;
    }

    public function generate() {
        $prepared_data = [];

        $data = $_POST['data'];
        $data = explode('&', $data);

        foreach ($data as $index => $pair) {
            $pair = explode('=', $pair);

            if(!trim($pair[1])) {
                continue;
            }

            $keys = preg_split('/[\[\]]+/', urldecode($pair[0]), -1, PREG_SPLIT_NO_EMPTY);
            
            // $keys[0] is property name (order, page_variables, etc.)
            // $keys[1] is a page id (as usual)
            // So, if we haven't property in prepared data, set it up
            if(!array_key_exists($keys[0], $prepared_data)) {
                
                if(isset($keys[1])) {
                    // page variables, set it
                    $prepared_data[$keys[0]] = array(
                        $keys[1] => $pair[1]
                    );
                } else {
                    // just post id, set it too
                    $prepared_data[$keys[0]] = array(
                        $pair[1]
                    );
                }

            // But, if we have it already and post_id is not in $prepared_data, it means, we set up page variables
            } elseif(isset($keys[1]) && !array_key_exists($keys[1], $prepared_data[$keys[0]])) {

                // page variables in raw format stored in $pair array
                $prepared_data[$keys[0]][$keys[1]] = $pair[1];

            // And finally, if we haven't property and $keys[1] isn't set...
            } else {

                // ...it means, this is just post id that we need set separately
                // $pair[1] in these times can be just post_id integer
                // so $keys[0] now is `posts`
                $prepared_data[$keys[0]][] = $pair[1];
                
            }
        }

        $template = $this->Config->get('defaults.templates.client.default-template');
        if(isset($prepared_data['template'])) {
            $template = $prepared_data['template'][0];
        }

        $shortcodes = [
            'main' => [],
        ];

        $shortcode_defaults = $this->Config->get('defaults.shortcode');
        $page_variables = $this->Config->get('defaults.page-variables');
        $template_variables = $this->TemplateVariablesRepository->get($template);

        if(isset($prepared_data['page_variables']) && isset($prepared_data['posts'])) {

            $last_id = null;

            foreach ($prepared_data['posts'] as $post_id) {
                if(isset($prepared_data['page_variables'][$post_id])) {
                    $current_page_variables = $this->PageVariablesDecoder->decode_page_variables(urldecode($prepared_data['page_variables'][$post_id]), $post_id, false);

                    $shortcodes[$post_id] = array_merge(array(
                        'posts' => $post_id,
                    ), $current_page_variables);

                    $last_id = null;
                } else {
                    if(is_null($last_id)) {
                        $last_id = $post_id;
                    }

                    if(isset($shortcodes[$last_id]['posts'])) {
                        $shortcodes[$last_id]['posts'] .= ', ' . $post_id;
                    } else {
                        $shortcodes[$last_id]['posts'] = $post_id;
                    }
                }
            }

            unset($prepared_data['posts']);
            unset($prepared_data['page_variables']);
            unset($last_id);
        }

        foreach ($prepared_data as $param => $value) {
            if($param === 'posts') {
                $shortcodes['main'][$param] = implode(',', $value);
            } else {
                $shortcodes['main'][$param] = $value;
            }
        }

        // Build shortcodes up
        $output = array();
        $is_main_open = false;
        foreach ($shortcodes as $key => $attrs) {
            if($key === 'main') { // main shortcode

                $output[] = '[tea_page_content';

                foreach ($attrs as $attr_name => $attr_value) {
                    if(is_array($attr_value)) {
                        $attr_value = implode(',', $attr_value);
                    }

                    $output[] = ' '. $attr_name . '="'. urldecode(htmlspecialchars($attr_value)).'"';
                }

                if(count($shortcodes) > 1) {    
                    $is_main_open = true;
                } else {
                    $output[] = "/";
                }

                $output[] = "]\r\n";

            } else { // key is post_id, inner shortcode

                $output[] = '[tea_page_content';

                foreach ($attrs as $attr_name => $attr_value) {
                    if(is_array($attr_value)) {
                        $attr_value = implode(',', $attr_value);
                    }

                    $output[] = ' '. $attr_name . '="'.urldecode(htmlspecialchars($attr_value)).'"';
                }

                $output[] = "/]\r\n";

            }
        }

        if($is_main_open) {
            $output[] = "[/tea_page_content]";
        }

        echo implode('', $output);
    }
}