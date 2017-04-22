<?php

namespace TeaPageContent\App\Entries;

use TeaPageContent\App\Traits;

/**
 * Extract post data from WP_Post objects
 * and from passed arrays.
 */
class Extractor {
    use Traits\Predicates\Common;
    use Traits\Predicates\Posts;
    use Traits\Utils\Common;
    
    public function get_post_data(\WP_Post $post) {
        $data = array(
            'id' => $post->ID,
            'title' => $post->post_title,
            'content' => do_shortcode($this->get_actual_post_content($post)),
            'thumbnail' => get_the_post_thumbnail($post->ID),
            'link' => get_permalink($post->ID)
        );

        return $data;
    }

    public function get_actual_post_content(\WP_Post $post) {
        $content = null;

        if(!$this->is_post_content_contain_more_tag($post->post_content)) {
            $content = get_the_excerpt();

            if(!trim($content)) {
                $content = get_the_content();
            }
        } else {
            $content = get_the_content();
        }

        return $content;
    }

    public function get_actual_post_in_param(Array $params) {
        $post__in = $this->get_post_in_from($params);

        if($this->is_post_in_not_array($post__in)) {
            $parsed_ids = $this->explode_by_comma($post__in);

            if($this->is_array_and_elements_not_empty($parsed_ids)) {
                $post__in = array_filter($parsed_ids);
            }
        }

        return $post__in;
    }

    private function get_post_in_from(Array $params) {
        if($this->is_post_in_exists_in($params)) {
            return $params['post__in'];
        }
        
        return [];
    }
}