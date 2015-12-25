<?php
/**
 * @package Tea Page Content
 * @version 1.0.0
 */

class TeaPageContent_Helper {
	/**
	 * Private constructor
	 * 
	 * @return void
	 */
	private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}

    /**
     * Calculate and return a path for passed template
     * 
     * @param string $template 
     * @return string
     */
    public static function getTemplatePath($template) {
    	$templatePath = locate_template('templates/'. $template . '.php');

		if(!$templatePath) {
			$templatePath = TEA_PAGE_CONTENT_PATH . '/templates/' . $template . '.php';
		}

		$templatePath = apply_filters(
			'tpc_get_template_path_in_render',
			$templatePath
		);

		return $templatePath;
    }

    /**
     * Load layout, pass params in layout
     * and return created html code. If template
     * is not exists or empty returns null
     * 
     * @param array $params 
     * @param string $template 
     * @return mixed
     */
    public static function renderTemplate($params, $template) {
    	$content = null;

    	ob_start();
		foreach ($params as $title => $value) {
			set_query_var($title, $value);
		}

		load_template($template, false);

		$content = ob_get_contents();
		
		$content = apply_filters(
			'tpc_render_template',
			$content
		);
		ob_end_clean();

		return $content;
    }

    /**
     * Locate and render template with params
     * 
     * @param string $template 
     * @param array $params 
     * @return mixed
     */
	public static function displayTemplate($template = 'default', $params = array()) {
		$templatePath = TeaPageContent_Helper::getTemplatePath($template);

		$content = TeaPageContent_Helper::renderTemplate($params, $templatePath);

		if($content) {
			echo $content;
		}
	}

	/**
	 * Locate, render and return html code of the template
	 * 
	 * @param string $template 
	 * @param array $params 
	 * @return mixed
	 */
	public static function getRenderedTemplate($template = 'default', $params = array()) {
		$templatePath = TeaPageContent_Helper::getTemplatePath($template);

		$content = TeaPageContent_Helper::renderTemplate($params, $templatePath);

		return $content;
	}

	/**
	 * Returns all posts by all types.
	 * Filters finded posts, removes useless data.
	 * Only ID and post_type will be returned.
	 * All posts will be sorted by type.
	 * 
	 * @param mixed $ids String or array with ID of desired posts
	 * @return array
	 */
	public static function getPosts($ids = null, $order = 'desc') {
		global $post;

		if(!is_array($ids) && $ids) {
			$ids = explode(',', $ids);
		}

		$postsPrepared = array();

		// Determine post types
		$types = array('post', 'page');
		$types = apply_filters('tpc_post_types', $types);

		// Determine post params
		$postsParams = array(
			'posts_per_page' => 0,
			'post_type' => $types,
			'order' => $order
		);
		$postsParams = apply_filters('tpc_post_params', $postsParams);

		if(count($ids) && array_filter($ids)) {
			$postsParams['post__in'] = $ids;
		}

		$custom_query = new WP_Query($postsParams);

		while($custom_query->have_posts()) {
			$custom_query->the_post();

			$post = $custom_query->post;

			$content = $post->post_content;
			
			if(strpos($content, '<!--more-->') === false) {
				$content = get_the_excerpt();

				if(!trim($content)) {
					$content = get_the_content();
				}
			} else {
				$content = get_the_content();
			}

			$postsPrepared[$post->post_type][] = array(
				'id' => $post->ID,
				'title' => $post->post_title,
				'content' => do_shortcode($content),
				'thumbnail' => get_the_post_thumbnail($post->ID),
				'link' => get_permalink($post->ID)
			);
		}
		wp_reset_postdata();

		// Here you can filter all returned posts
		// as assoc 2-dimensional array
		$postsPrepared = apply_filters(
			'tpc_prepared_posts',
			$postsPrepared
		);

		return $postsPrepared;
	}

	/**
	 * Gets a template list from template directories.
	 * Return array or null
	 * 
	 * @return mixed
	 */
	public static function getTemplates() {
		$directories = array(
			'plugin' => TEA_PAGE_CONTENT_PATH . '/templates/',
			'theme' => get_template_directory() . '/templates/'
		);

		$templates = array(
			'default', 'default-padded'
		);

		foreach ($directories as $type => $dir) {
			if(!is_dir($dir)) continue;
			$templates = array_merge($templates, array_filter(scandir($dir), function(&$item) {
				if(!is_dir($item) && substr($item, 0, 4) == 'tpc-') {
					$item = str_replace('.php', '', $item);
					return true;
				}

				return false;
			}));
		}

		// Here you can manage template list
		$templates = apply_filters(
			'tpc_get_templates',
			$templates
		);

		if(count($templates)) {
			return $templates;
		}

		return null;
	}
}