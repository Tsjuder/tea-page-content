<?php
/**
 * @package Tea Page Content
 * @version 1.1.1
 */

class TeaPageContent_Shortcode {
	/**
	 * Main shortcode for this plugin
	 * Returns html code of rendered shortcode,
	 * or false if output is empty
	 * 
	 * @param array $userAttrs 
	 * @return mixed
	 */
	public static function tea_page_content($userAttrs) {
		$output = false;

		$helper = new TeaPageContent_Helper;

		// Shortcode defaults
		$defaults = TeaPageContent_Config::get('defaults.shortcode');
		
		// Merge defaults and user attrs
		$attrs = shortcode_atts($defaults, $userAttrs, 'tea-page-content');

		// Get template variables for chosen template
		$variables = $helper->getVariables($attrs['template']);
		
		// Pre-create template variables, if exists
		// @todo вынести в отдельную функцию, be DRY
		$attrs['template_variables'] = array();
		foreach ($variables as $variable => $value) {
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

		// If params is not empty render shortcode
		if(!empty($attrs) && !empty($params['entries'])) {
			$templatePath = $helper->getTemplatePath($attrs['template']);

			$output = $helper->renderTemplate($params, $templatePath);
		}

		return $output;
	}
}