<?php
/**
 * @package Tea Page Content
 * @version 1.2.2
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
	 * @param string $mode
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

		// Filter by post status...
		if(!isset($params['post_status'])) {
			$params['post_status'] = $this->_config->get_current('defaults.posts.post_status');
		}

		// ...and by protected settings too
		if(!isset($params['has_password'])) {
			$params['has_password'] = $this->_config->get_current('defaults.posts.has_password');
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

					// Here you can modify current variable
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
	 * @param string $mode
	 * @return array
	 */
	public function getParams($instance, $mode = 'group') {
		$params = array();

		// Set the correct value of post ids. It may be string or array
		$post__in = null;
		if(!empty($instance['posts']) || !empty($instance['id'])) { // @todo сделать так, чтобы айдишники постов были десериализованы и превращены в строку
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
			// make dis shit dry {3}
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

				unset($instance['template_variables']);
			}

			// ... and page variables array too
			// At this case we should merge every entry array
			// with page variables array from instance.
			if(isset($instance['page_variables'])) {
				foreach ($params['entries'] as &$entry) {
					$entry_id = $entry['id'];

					// Get array with page variables
					$page_variables = $this->getPageVariables($instance['page_variables'], $entry_id);

					// Then we can merge it with original entry.
					// By default, original values in entry will be OVERRIDE,
					// and if you want change this behavior,
					// you can use filter `tpc_get_page_variables`
					$entry = array_merge($entry, $this->preparePageVariablesForMerge($page_variables));
				}
				unset($entry);

				unset($instance['page_variables']);
			}
		}

		// Instance should be in every case
		// @todo why i always use widget defaults, when i use this function in shortcode too? need fix it
		$params['instance'] = $instance + $this->_config->get('defaults.widget', 'per-page'); // @deprecated since 1.1

		// Set caller
		if(isset($params['instance']['caller'])) {
			$params['caller'] = $params['instance']['caller'];
			unset($params['instance']['caller']);
		}

		$params = apply_filters('tpc_get_params', $params);

		return $params;
	}

	/**
	 * Get page variables from global (passed) array for one specified entry.
	 * 
	 * @param array $page_variables
	 * @param int|null $entry_id 
	 * @return array
	 */
	public function getPageVariables($page_variables, $entry_id = null) {
		$result = array();

		if(is_array($page_variables) && !empty($page_variables)) {

			if(!is_null($entry_id) && isset($page_variables[$entry_id])) {
				$query_string = $page_variables[$entry_id];

				$decoded = $this->decodePageVariables($query_string, $entry_id);

				if($decoded) {
					$result = $decoded;
				}
			} elseif(is_null($entry_id)) {
				foreach ($page_variables as $pv_entry_id => $query_string) {
					$decoded = $this->decodePageVariables($query_string, $pv_entry_id);
					
					if($decoded) {
						$result[$pv_entry_id] = $decoded;
					}
				}
			}

		}

		return $result;
	}

	/**
	 * Remove prefix before merging page variables with entry properties.
	 * Need this because page variables should override some properties of entry.
	 * 
	 * @param array $page_variables 
	 * @return array
	 */
	public function preparePageVariablesForMerge($page_variables) {
		$page_var_prefix = $this->_config->get('system.page-variables.prefix');

		$prepared_variables = array();
		foreach ($page_variables as $variable => $value) {
			$unprefixed_title = str_replace($page_var_prefix, '', $variable);

			$prepared_variables[$unprefixed_title] = $value;
		}

		return $prepared_variables;
	}

	/**
	 * This function gets params that have a direct impact on content
	 * and appearance of it. This is entries, page- and template-level variables.
	 * 
	 * @param array $instance Original instance
	 * @param array $params Original params array
	 * @param array $exclude Determine field what we want exclude from final array
	 * 
	 * @return array
	 */
	public function getContentRelevantParams($instance, $params, $exclude = array()) {
		if(!in_array('page_variables', $exclude)) {
			// ... and page variables array too
			// At this case we should merge every entry array
			// with page variables array from instance.
			if(isset($instance['page_variables'])) {
				foreach ($params['entries'] as &$entry) {
					$entry_id = $entry['id'];

					// If page variables for current entry is exists...
					if(array_key_exists($entry_id, $instance['page_variables'])) {
						// ... cet string with raw encoded data...
						$query_string = $instance['page_variables'][$entry_id];
						
						// ... and parse it
						$page_variables = $this->decodePageVariables($query_string, $entry_id);
						
						// Then we can merge it with original entry.
						// By default, original values in entry will be OVERRIDE,
						// and if you want change this behavior,
						// you can use filter `tpc_get_page_variables`
						$entry = array_merge($entry, $page_variables);
					}
				}

				unset($instance['page_variables']);
			}
		}

		return $params;
	}

	/**
	 * Parse passed query string with raw page variables data
	 * to associative array with decoded page variables
	 * 
	 * @param string $query_string 
	 * @param int $entry_id
	 * @return array
	 */
	public function decodePageVariables($query_string, $entry_id = null, $apply_rules = true) {
		$page_variables = array();

		if(trim($query_string)) {
			// Here you can change original query string
			$query_string = apply_filters('tpc_page_variables_raw', $query_string, $entry_id);

			// Parse query and decode every piece of cake
			foreach(explode('&', $query_string) as $elem) {
				$exploded = array_map(function($elem) {
					return urldecode($elem);
				}, explode('=', $elem));

				if($exploded[0] && isset($exploded[1]) && trim($exploded[1])) {

					// @todo использовать правила (rules)
					// @todo make dis shit dry {2}
					if($apply_rules) {
						switch ($exploded[0]) {
							case 'page_thumbnail':
								if(is_numeric($exploded[1])) {
									if(is_admin()) {
										$exploded[1] = wp_get_attachment_url($exploded[1]);
									} else {
										$thumbnail_size = 'post-thumbnail'; // @todo в конфиг
										$thumbnail_size = apply_filters('tpc_thumbnail_size', $thumbnail_size, $exploded[1], $entry_id);

										$exploded[1] = wp_get_attachment_image($exploded[1], $thumbnail_size);
									}
								}
							break;
						}
					}

					$page_variables[$exploded[0]] = $exploded[1];
				}
			}
		}

		// Here you can manage structure and data of page variables for every entry
		$page_variables = apply_filters('tpc_page_variables', $page_variables, $entry_id);

		return $page_variables;
	}

	/**
	 * Extract page variables from shortcode attributes
	 * 
	 * @param array $attrs 
	 * @return array
	 */
	public function extractPageVariables($attrs, $entry_id = null, $apply_rules = true) {
		$existed_variables = $this->_config->get('defaults.page-variables');

		$extracted_variables = array_intersect_key($attrs, $existed_variables);

		// @todo make dis shit dry {2}
		if($apply_rules) {
			foreach ($extracted_variables as $title => &$value) {
				switch ($title) {
					case 'page_thumbnail':
						if(is_numeric($value)) {
							if(is_admin()) {
								$value = wp_get_attachment_url($value);
							} else {
								$thumbnail_size = 'post-thumbnail'; // @todo в конфиг
								$thumbnail_size = apply_filters('tpc_thumbnail_size', $thumbnail_size, $value, $entry_id);

								$value = wp_get_attachment_image($value, $thumbnail_size);
							}
						}
					break;
				}
			}
		}

		return $extracted_variables;
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
				if(!is_dir($item) && substr($item, 0, 4) === 'tpc-') {
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

	public function getMappedSettings() {
		$result = array();

		$map = $this->_config->get_config_map();

		foreach ($map as $config_path => $params) {
			$alias = $this->convertConfigPathToSetting($config_path);

			$default_value = $this->_config->get_current($config_path);

			$params['default'] = $default_value;

			$result[$alias] = $params;
		}

		return $result;
	}

	public function convertSettingToConfigPath($setting) {
		if(!is_string($setting)) {
			return false;
		}

		return str_replace(array('tpc_', '__'), array('', '.'), $setting); // @todo make dis shit dry {4}
		// get tpc_ via config
	}

	public function convertConfigPathToSetting($config_path) {
		if(!is_string($config_path)) {
			return false;
		}

		return 'tpc_' . str_replace('.', '__', $config_path);
	}

	public function updateDeprecatedNoticeOption() {
		$version = $this->_config->get('system.versions.plugin');

		if(!get_option('tpc_deprecated_notice')) {
			add_option('tpc_deprecated_notice', $version, '', 'no');
		} else {
			update_option('tpc_deprecated_notice', $version, 'no');
		}
	}
}