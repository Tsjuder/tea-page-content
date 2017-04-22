<?php

namespace TeaPageContent\App\Entries;

use TeaPageContent\App\Traits;

class Repository {
    use Traits\Predicates\Posts;
    use Traits\Utils\Posts;

    private static $posts = [];
    
    private $EntryParams = null;
    private $EntryFormatter = null;

    public function __construct(Params $EntryParams, Formatter $EntryFormatter) {
        $this->EntryParams = $EntryParams;
        $this->EntryFormatter = $EntryFormatter;
    }

    public function get(Array $params = [], $mode = 'group') {
        $posts = [];

        $params = $this->EntryParams->get_post_query_params($params);

        if($this->is_post_in_exists_in($params)) {
            $posts = $this->get_posts_and_store($params, $mode);
        }

        return $posts;
    }

    private function get_posts_and_store($params, $mode) {
        if($this->is_post_in_an_empty_array($params['post__in'])) {
            $posts = $this->get_and_store_all_posts($params, $mode);
        } else {
            $posts = $this->get_and_store_posts_by_hash($params, $mode);
        }

        return $posts;
    }

    private function get_and_store_all_posts($params, $mode) {
        if($this->is_all_posts_already_stored($mode)) {
            $posts = $this->get_all_stored_posts($mode);
        } else {
            $posts = $this->get_formatted_posts_by_params($params, $mode);

            $this->store_all_finded_posts($posts, $mode);
        }

        return $posts;
    }

    private function get_and_store_posts_by_hash($params, $mode) {
        $posts_hash = $this->get_posts_hash($params['post__in']);

        if($this->is_posts_already_stored_by_hash($posts_hash, $mode)) {
            $posts = $this->get_stored_posts_by_hash($posts_hash, $mode);
        } else {
            $posts = $this->get_formatted_posts_by_params($params, $mode);

            $this->store_by_hash_finded_posts($posts, $posts_hash, $mode);
        }

        return $posts;
    }

    private function is_all_posts_already_stored($mode) {
        return isset(self::$posts['all']) && isset(self::$posts['all'][$mode]);
    }

    private function is_posts_already_stored_by_hash($hash, $mode) {
        return isset(self::$posts[$hash]) && isset(self::$posts[$hash][$mode]);
    }

    private function get_all_stored_posts($mode) {
        return self::$posts['all'][$mode];
    }

    private function get_stored_posts_by_hash($hash, $mode) {
        return self::$posts[$hash][$mode];
    }

    private function store_all_finded_posts($posts, $mode) {
        if(!isset(self::$posts['all'])) {
            self::$posts['all'] = [];
        }

        self::$posts['all'][$mode] = $posts;
    }

    private function store_by_hash_finded_posts($posts, $hash, $mode) {
        if(!isset(self::$posts['all'])) {
            self::$posts['all'] = [];
        }

        self::$posts['all'][$mode] = $posts;
    }

    public function get_formatted_posts_by_params(Array $params, $mode = 'group') {
        $posts = [];

        switch ($mode) {
            case 'flatten': // Flatten mode is a one-level array
                $posts = $this->EntryFormatter->format_posts_in_flatten_mode($params);
                break;

            case 'group': // Group mode, by default, is a assoc array, splitted by post type
            default:
                $posts = $this->EntryFormatter->format_posts_in_group_mode($params);
                break;
        }

        $posts = apply_filters('tpc_prepared_posts', $posts, $mode);

        return $posts;
    }
}