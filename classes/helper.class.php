<?php
/**
 * @package Tea Page Content
 * @version 1.1.1
 */

class TeaPageContent_Helper {
	private $_config;

	/**
	 * Constructor
	 * 
	 * @return void
	 */
	public function __construct() {
		// Gets instance of config object
		$this->_config = TeaPageContent_Config::getInstance();
	}

	/**
	 * Calculate and return a path for passed template. If template
	 * not passed, returns null
	 *
	 * @todo нужно сделать так, чтобы функция возвращала null при отсутствии шаблона
	 * 
	 * @param string $template 
	 * @return string|null
	 */
	public function getTemplatePath($template) {
		if(!$template) return null;

		// Check template in theme folder
		$templatePath = locate_template('templates/'. $template . '.php');

		// If not exists, check in plugin folder
		if(!$templatePath) {
			$templatePath = TEA_PAGE_CONTENT_PATH . '/templates/' . $template . '.php';
		}

		// Here you can manage template path
		$templatePath = apply_filters('tpc_get_template_path', $templatePath);

		return $templatePath;
	}

	/**
	 * Load layout, pass params in layout
	 * and return created html code. If template
	 * is not exists or empty returns null
	 * 
	 * @param array $params 
	 * @param string $template 
	 * @return string|null
	 */
	public function renderTemplate($params, $template) {
		if(!$template) return null;

		ob_start();
		foreach ($params as $title => $value) {
			set_query_var($title, $value);
		}

		load_template($template, false);

		$content = ob_get_contents();

		// Here you can manage rendered content
		$content = apply_filters('tpc_render_template',	$content);

		ob_end_clean();

		return $content;
	}

	/**
	 * Gets all exist types of posts and returns as array
	 * 
	 * @return array
	 */
	public function getPostTypes() {
		// By default this OR
		$operator = $this->_config->get('system.posts.types-operator');
		$operator = apply_filters('tpc_post_types_operator', $operator);

		// Gets arguments for wp_query that getting post types
		// You can manage this for customize wp_query
		$args = $this->_config->get('defaults.post-types');
		$args = apply_filters('tpc_post_types_args', $args);
		
		// Gets post types by created arguments
		$types = get_post_types($args, 'names', $operator);
		$types = apply_filters('tpc_post_types', $types);

		return $types;
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
	public function getPosts($params = array(), $mode = 'group') {
		$postsPrepared = array();

		// Determine post types
		$types = $this->getPostTypes();
		$params['post_type'] = $types;

		// Set up some necessary defaults
		// For defaults we will gets params from config for widget,
		// because in system any choice has no different
		if(!isset($params['order'])) {
			$params['order'] = $this->_config->get('defaults.widget.order');
		}

		$params['posts_per_page'] = $this->_config->get('defaults.widget.per-page');

		// Re-organize id string into array of ids
		if(isset($params['post__in']) && !is_array($params['post__in']) && $params['post__in']) {
			$post__in = explode(',', $params['post__in']);

			if(count($post__in) && array_filter($post__in)) {
				$params['post__in'] = $post__in;
			}
		}

		// Filter param array
		$params = apply_filters('tpc_post_params', $params);

		// Create custom WP Query
		$postsQuery = new WP_Query($params);
		while($postsQuery->have_posts()) {
			$postsQuery->the_post();

			$post = $postsQuery->post;

			// Smart detection for flexible displaying of more link
			// More link should be visible in all possible cases,
			// without case if there is only one text w/o more tag or excerpt
			$content = null;
			if(strpos($post->post_content, '<!--more-->') === false) {
				$content = get_the_excerpt();

				if(!trim($content)) {
					$content = get_the_content();
				}
			} else {
				$content = get_the_content();
			}

			// Fill result array with needly fields
			$postData = array(
				'id' => $post->ID,
				'title' => $post->post_title,
				'content' => do_shortcode($content),
				'thumbnail' => get_the_post_thumbnail($post->ID),
				'link' => get_permalink($post->ID)
			);

			// Post data can be in different modes
			switch ($mode) {
				// Flatten mode is a one-level array
				case 'flatten':
					$postsPrepared[] = $postData;
				break;

				// Group mode, by default, is a assoc array, splitted by post type
				case 'group':
				default:
					$postsPrepared[$post->post_type][] = $postData;
				break;
			}
		}
		wp_reset_postdata();

		// Main filter for all getting posts
		$postsPrepared = apply_filters('tpc_prepared_posts', $postsPrepared);

		return $postsPrepared;
	}

	/**
	 * Gets variables from template and mix it with defaults. 
	 * Actual for widget and shortcode.
	 * 
	 * @param string $template 
	 * @return array|null
	 */
	public function getVariables($template) {
		$templatePath = $this->getTemplatePath($template);

		if(!$templatePath) return null;
		
		$variables = array();
		$regexp = '/(?![@param*])((?:"[^"]+")|(?:[\S]+))/i';

		// Try to read template file
		if($handle = fopen($templatePath, 'r')) {
			$isHeader = false;

			// Read all variables from template, parse it and validate
		    while(($line = fgets($handle)) !== false) {
		    	if($isHeader && strpos($line, '@param') !== false) {
		    		$variable = preg_grep($regexp, preg_split($regexp, $line, -1, PREG_SPLIT_DELIM_CAPTURE));
		    		$variable = array_values($variable);

		    		// Pass empty or incorrect variables - first field is required
		    		if(empty($variable)) continue;

		    		// Default mask is: {name} {type} {defaults}
		    		$mask = $this->_config->get('system.template-variables.mask');
		    		$defaults = $this->_config->get('defaults.template-variables');

		    		// Filter every part of variable by mask
		    		foreach ($mask as $index => $item) {
		    			$is_index = isset($variable[$index]);

		    			if(!$is_index && isset($defaults[$item])) {
		    				$variable[$item] = $defaults[$item];
		    			} elseif($is_index) {
		    				$variable[$item] = $variable[$index];
		    			}

		    			if($item === 'defaults') {
		    				$variable[$item] = explode('|', $variable[$item]);
		    			}

		    			if($is_index) {
		    				unset($variable[$index]);
		    			}
		    		}

		    		// Here possible to modify current variable
		    		$variable = apply_filters('tpc_get_template_variable', $variable);

		    		$variables[$variable['name']] = $variable;
		        }

		        // Check for header of the variable-part...
		        if(strpos($line, '/**') !== false) {
		        	$isHeader = true;
		        } elseif(strpos($line, '*/') !== false) { // ...and out if the variable-part is over
		        	$isHeader = false;

		        	break;
		        }
		    }

		    fclose($handle);
		}

		// Filter for all variables for passed template
		$variables = apply_filters('tpc_get_template_variables', $variables);

		return $variables;
	}

	/**
	 * Returns a full list with all necessary params
	 * for getting posts by passed instance from
	 * widget or shortcode
	 * 
	 * First param should be an associative array
	 * 
	 * @param array $instance 
	 * @return array
	 */
	public function getParams($instance, $mode = 'group') {
		// Instance should be in every case
		$params = array(
			'instance' => $instance + $this->_config->get('defaults.widget', 'per-page') // @deprecated since 1.1
			// @todo сделать так, чтобы айдишники постов были десериализованы и превращены в строку
		);

		// Set the correct value of post ids. It may be string or array
		$post__in = null;
		if(!empty($instance['posts']) || !empty($instance['id'])) {
			// @deprecated since 1.1, for shortcodes only
			$actual = !empty($instance['posts']) ? $instance['posts'] : $instance['id'];

			// Check for serialized string, this is a Wordpress function
			if(is_serialized($actual)) {
				$post__in = unserialize($actual);
			} else {
				$post__in = $actual;
			}
		}
		
		// If current instance have post ids...
		if($post__in && !empty($post__in)) {
			$order = $this->_config->get('defaults.widget.order');

			if(isset($instance['order']) && $instance['order']) {
				$order = $instance['order'];
			}

			// ...then we can get the entries
			$params['entries'] = $this->getPosts(array(
				'post__in' => $post__in,
				'order' => $order
			), $mode);

			$params['count'] = count($params['entries']);

			// Here we should pass template variables array
			// Because dashes not allowed in variable names,
			// we need use lower-dash
			$params['template_variables'] = array();
			if(isset($instance['template_variables'])) {
				$params['template_variables'] = $instance['template_variables'];
			}
		}

		$params = apply_filters('tpc_get_params', $params);

		return $params;
	}

	/**
	 * Gets a template list from template directories.
	 * 
	 * @return array
	 */
	public function getTemplates() {
		// In these directories should be template files
		$directories = $this->_config->get('system.template-directories');
		$directories = apply_filters('tpc_template_directories', $directories);

		// Built-in templates
		$templates = $this->_config->get('system.predefined-templates');

		// Check all directories for custom templates
		foreach ($directories as $type => $dir) {
			if(!is_dir($dir)) continue;

			// Filter files by mask
			$templates = array_merge($templates, array_filter(scandir($dir), function(&$item) {
				// If current file has a substring 'tpc-', return it
				if(!is_dir($item) && substr($item, 0, 4) == 'tpc-') {
					$item = str_replace(array('.php', '.html', '.htm', '.tpl'), '', $item);
					return true;
				}

				return false;
			}));
		}

		// Here you can manage template list
		$templates = apply_filters('tpc_get_templates', $templates);

		return $templates;
	}
}