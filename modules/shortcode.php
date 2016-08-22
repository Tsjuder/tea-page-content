<?php
/**
 * @package Tea Page Content
 * @version 1.2.0
 */

class TeaPageContent_Shortcode {
	/**
	 * Main shortcode for this plugin
	 * Returns html code of rendered shortcode,
	 * or false if output is empty
	 * 
	 * @param array $userAttrs 
	 * @param string|null $content
	 * @return mixed
	 */
	public static function tea_page_content($userAttrs, $content = null) {
		$output = false;

		$helper = new TeaPageContent_Helper;
		$config = TeaPageContent_Config::getInstance();

		// Shortcode defaults
		$defaults = $config->get('defaults.shortcode');
		
		// Merge defaults and user attrs
		$attrs = shortcode_atts($defaults, $userAttrs, 'tea-page-content');

		// Get template variables for chosen template
		$template_variables = $helper->getVariables($attrs['template']);

		// If we have content, it means than user want set page level vars
		if(!is_null($content) && trim($content)) {
			// Here we will store all parsed pieces of inner shortcodes
			// Because we need render it in single layout
			$innerAttrsBundle = array();
			$params = array();

			// Split content data by shortcodes
			$content = preg_split('/(\[tea_page_content.*\])/i', $content, null, PREG_SPLIT_DELIM_CAPTURE);

			// And filter it for excluding trash data
			$content = array_filter($content, function($elem) {
				if(preg_match('/^\[tea_page_content/i', $elem)) {
					return true;
				}

				return false;
			});

			// Then, grep parsed data step by step
			foreach ($content as $index => $shortcode) {
				// Split every piece to params, with two separate groups for title and value
				$current = preg_split('/(\w+)=\"([^"]+)\"/ui', $shortcode, null, PREG_SPLIT_DELIM_CAPTURE);

				$innerAttrsBundle[$index] = array();

				// Now combine title and value into one param
				for($i = 0, $count = count($current); $i < $count; $i++) {
					$attr = $current[$i];

					// Check for non-page-level variables, if it exists, skip two iterations
					if($attr !== 'posts' && in_array($attr, array_keys($defaults))) {
						$i++;

						continue;
					}

					// Combine only if current piece is really param...
					if($i < $count-1 && preg_match('/^[^\[][\w\d]+/ui', $attr)) {
						$nextAttr = $current[$i+1];

						$innerAttrsBundle[$index][$attr] = $nextAttr;

						$i++;
					}
				}
			}

			// Get the entries for every piece
			$params['entries'] = array();
			foreach ($innerAttrsBundle as $innerAttrs) {
				if(!array_key_exists('posts', $innerAttrs)) {
					continue;
				}

				$pageVariables = $helper->extractPageVariables($innerAttrs);

				$entries = $helper->getPosts(array(
					'post__in' => $innerAttrs['posts']
				), 'flatten');

				foreach ($entries as &$entry) {
					
					// Then we can merge it with original entry.
					// By default, original values in entry will be OVERRIDE,
					// and if you want change this behavior,
					// you can use filter `tpc_get_page_variables`
					$entry = array_merge($entry, $pageVariables);
				}
				unset($entry);

				$params['entries'] = array_merge($params['entries'], $entries);
			}

			$params = array_merge($params, $helper->getParams($attrs, 'flatten'));

		} else { // If we haven't content. It means that this is usual shortcode
			
			// Pre-create template variables, if exists
			// @todo вынести в отдельную функцию, be DRY
			$attrs['template_variables'] = array();
			foreach ($template_variables as $variable => $value) {
				if($value['type'] === 'caption') continue;
				
				if(isset($userAttrs[$variable])) {
					$attrs['template_variables'][$variable] = $userAttrs[$variable];
				} else {
					switch ($value['type']) {
						case 'checkbox':
							if(reset($value['defaults'])) {
								$attrs['template_variables'][$variable] = $variable;	
							}
						break;
						
						default:
							$attrs['template_variables'][$variable] = reset($value['defaults']);
						break;
					}
				}
			}

			// Gets params for this shortcode
			$params = $helper->getParams($attrs, 'flatten');
		}

		// If params is not empty render shortcode
		if(!empty($attrs) && !empty($params['entries'])) {
			$templatePath = $helper->getTemplatePath($attrs['template']);

			$output = $helper->renderTemplate($params, $templatePath);
		}

		return $output;
	}
}