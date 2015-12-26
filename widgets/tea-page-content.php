<?php
/**
 * @package Tea Page Content
 * @version 1.0.0
 */

class TeaPageContent_Widget extends WP_Widget {
	/**
	 * This variable stores itself all widget's params.
	 * 
	 * @var array
	 */
	private $params = null;

	/**
	 * Main constructor, set up all global preferenses
	 * and parameters, e.g. description, title, etc.
	 * 
	 * @return void
	 */
	public function __construct() {
		parent::__construct(
			'tea-page-content', 
			__('Tea Page Content', 'tea-page-content'),
			array(
				'description' => __('Allows display any content of any page or post.', 'tea-page-content')
			)
		);

		// Set defaults
		$this->params = array(
			'title' 	=> '',
			'posts' 	=> '',
			'thumbnail' => 1,
			'template'  => 'default',
			'order'     => 'desc'
		);
	}

	/**
	 * Creates and render form for
	 * frontend part of this widget
	 * 
	 * @param array $args 
	 * @param array $instance 
	 * @return void
	 */
	public function widget($args, $instance) {
		$template = apply_filters(
			'tpc_get_template_path',
			$instance['template']
		);

		$params = array(
			'instance' => $instance
		);

		$ids = unserialize($instance['posts']);

		if(!empty($ids)) {
			$params['entries'] = TeaPageContent_Helper::getPosts($ids);
		}

		$params = apply_filters('tpc_get_params', $params);

		// Render form
		TeaPageContent_Helper::displayTemplate($template, $params);
	}

	/**
	 * Update params of this widget
	 * 
	 * @param array $newInstance 
	 * @param array $oldInstance 
	 * @return array
	 */
	public function update($newInstance, $oldInstance) {
		$instance = $oldInstance;
		// Set the instance
		foreach ($this->params as $param => $value) {
			if($param == 'posts') {
				$instance[$param] = serialize($newInstance[$param]);
			} else {
				$instance[$param] = $newInstance[$param];
			}
		}

		return $instance;
	}

	/**
	 * Creates and render a form for
	 * admin part of this widget
	 * 
	 * @param array $instance 
	 * @return void
	 */
	public function form($instance) {
		$instance = array_merge($this->params, (array)$instance);

		$template = apply_filters(
			'tpc_get_admin_template_path',
			'default-widget-admin'
		);

		$params = apply_filters(
			'tpc_get_admin_params',
			array(
				'instance' => $instance,
				'bind' => $this,
				'entries' => TeaPageContent_Helper::getPosts(),
				'templates' => TeaPageContent_Helper::getTemplates()
			)
		);

		// Render form
		TeaPageContent_Helper::displayTemplate($template, $params);
	}
}