<?php

namespace TeaPageContent\App\Entries;

/**
 * Format post' lists by mode.
 */
class Formatter {
    private $EntryExtractor = null;
    
    public function __construct(Extractor $EntryExtractor) {
        $this->EntryExtractor = $EntryExtractor;
    }

    public function format_posts_in_flatten_mode(Array $params) {
        $posts = [];
        
        $query = new \WP_Query($params);
        while($query->have_posts()) {
            $query->the_post();
            
            $posts[] = $this->EntryExtractor->get_post_data($query->post);
        }
        wp_reset_postdata();

        return $posts;
    }

    public function format_posts_in_group_mode(Array $params) {
        $posts = [];
        
        $query = new \WP_Query($params);
        while($query->have_posts()) {
            $query->the_post();

            if(!isset($posts[$query->post->post_type])) {
                $posts[$query->post->post_type] = [];
            }

            $posts[$query->post->post_type][] = $this->EntryExtractor->get_post_data($query->post);
        }
        wp_reset_postdata();

        return $posts;
    }
}