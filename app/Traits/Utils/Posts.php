<?php

namespace TeaPageContent\App\Traits\Utils;

trait Posts {
    private function get_posts_hash($posts) {
        if(!is_string($posts)) {
            $posts = serialize($posts);
        }
        
        return md5($posts);
    }
}