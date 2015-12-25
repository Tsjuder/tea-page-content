<?php
/**
 * @package Tea Page Content
 * @version 1.0.0
 */

class TeaPageContent_Shortcodes {
	/**
	 * Main shortcode for this plugin
	 * Returns html code of rendered shortcode,
	 * or false if output is empty
	 * 
	 * @param array $userAttrs 
	 * @return mixed
	 */
	public static function tea_page_content($userAttrs) {
		$defaults = array(
			'id' => null,
			'template' => 'default',
			'thumbhail' => true,
			'order' => 'desc'
		);

		$attrs = shortcode_atts($defaults, $userAttrs, 'tea-page-content');
		$params = array(
			'instance' => $attrs
		);

		$output = false;

		if($attrs['id']) {
			$params['entries'] = TeaPageContent_Helper::getPosts($attrs['id'], $attrs['order']);

			$params = apply_filters('tpc_get_params', $params);

			$output = TeaPageContent_Helper::getRenderedTemplate($attrs['template'], $params);
		}

		return $output;
	}
}