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
	 * Creates and render form for frontend part of this widget
	 * 
	 * @param array $args 
	 * @param array $instance 
	 * @return void
	 */
	public function widget($args, $instance) {
		$data = $args;

		$ids = unserialize($instance['posts']);

		$template = apply_filters('tpc_template_name', $instance['template']);

		$params = array(
			'instance' => $instance
		);
		
		if(!empty($ids)) {
			$params['entries'] = TeaPageContent_Helper::getPosts($ids);
		}

		$params = apply_filters('tpc_get_params', $params);

		if(!empty($args) && !empty($params['entries'])) {
			$data['title'] = apply_filters('widget_title', $instance['title']);
			$data['markup'] = TeaPageContent_Helper::getRenderedTemplate($template, $params);

			$content = $this->renderWidget($data);

			echo $content;
		}
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

		$params = array(
			'instance' => $instance,
			'bind' => $this,
			'entries' => TeaPageContent_Helper::getPosts(),
			'templates' => TeaPageContent_Helper::getTemplates()
		);
		$params = apply_filters('tpc_get_admin_params',	$params);

		// Render form
		TeaPageContent_Helper::displayTemplate($template, $params);
	}

	/**
	 * Private method for render widget's layout
	 * with pre-rendered template. Returns html-code of widget
	 * 
	 * @param array $data 
	 * @return string
	 */
	private function renderWidget($data) {
		ob_start();
		set_query_var('widget', $data);

		load_template(TEA_PAGE_CONTENT_PATH . '/templates/default-widget-client.php', false);

		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}
}