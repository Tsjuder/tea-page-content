<?php

namespace TeaPageContent\App\Traits\Predicates;

trait Posts {
    private function is_post_in_exists_in(Array $params) {
        return isset($params['post__in']) && !is_null($params['post__in']);
    }

    private function is_post_content_contain_more_tag($post_content) {
        return strpos($post_content, '<!--more-->') === false;
    }

    private function is_post_in_not_array($post__in) {
        return $post__in && !is_array($post__in);
    }

    private function is_post_in_an_empty_array($post__in) {
        return is_array($post__in) && empty($post__in);
    }
}