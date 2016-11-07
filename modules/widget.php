<?php
/**
 * @package Tea Page Content
 * @version 1.2.1
 */

class TeaPageContent_Widget extends WP_Widget {
	/**
	 * This variable stores itself all widget's params.
	 * 
	 * @var array
	 */
	private $params = null;

	private $_helper = null;
	private $_config = null;

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
			),
			array(
				'width' => 480
			)
		);

		// Get helper object
		$this->_helper = new TeaPageContent_Helper;

		// And config instance
		$this->_config = TeaPageContent_Config::getInstance();

		// Set defaults
		$this->params = $this->_config->get('defaults.widget', array('per-page', 'caller'));
	}

	/**
	 * Creates and render frontend part of this widget
	 * 
	 * @param array $args 
	 * @param array $instance 
	 * @return void
	 */
	public function widget($args, $instance) {
		$data = $args;

		// Here you can manage template of the widget
		$template = apply_filters('tpc_template_name', $instance['template']);

		$templatePath = $this->_helper->getTemplatePath($template);

		// Gets params for this widget
		$params = $this->_helper->getParams($instance, 'flatten');

		// Set up some necessary properties
		if(!empty($args) && !empty($params['entries'])) {
			$data['title'] = apply_filters('widget_title', $instance['title']);

			$data['markup'] = $this->_helper->renderTemplate($params, $templatePath);

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

		// We need pre-load template params
		$template = $newInstance['template'];
		$template_variables = $this->_helper->getVariables($template);

		$preparedInstance = $newInstance + $this->params + $template_variables;

		// @todo make dis shit dry {1}
		foreach ($preparedInstance as $param => $value) {
			if($param === 'posts') {
				$instance[$param] = serialize($newInstance[$param]);
			} elseif(array_key_exists($param, $this->params)) {
				if(isset($newInstance[$param])) {
					$instance[$param] = $newInstance[$param];
				} else {
					$instance[$param] = null;
				}
			} else {
				// Build up template params
				if(array_key_exists($param, $template_variables)) {
					$variable = $template_variables[$param];

					if($variable['type'] === 'caption') {
						continue;
					}

					if(!isset($instance['template_variables'])) {
						$instance['template_variables'] = array();
					}

					if(isset($newInstance[$param])) {
						$instance['template_variables'][$param] = $newInstance[$param];
					} else {
						$instance['template_variables'][$param] = null;
					}

				// Build up page level params
				} elseif($param === 'page_variables') {
					if(!isset($instance['page_variables'])) {
						$instance['page_variables'] = array();
					}

					foreach ($newInstance[$param] as $page_id => $variable_data) {
						
						if(trim($variable_data)) {
							$parsed_data = $this->_helper->decodePageVariables($variable_data, null, false);

							if(empty($parsed_data)) {
								continue;
							}

							$instance['page_variables'][$page_id] = $variable_data;
						}
						
					}
				}
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
		// Merging defaults with user defined params
		$instance = array_merge($this->params, (array)$instance);
		
		// Specify admin template
		$template = 'default-widget-admin'; // @todo вынести в конфиг
		$template = apply_filters('tpc_get_admin_template_path', $template);

		// Gets a path to the template
		$templatePath = $this->_helper->getTemplatePath($template);

		// Specify a params for template
		$params = array(
			'instance' => $instance,
			'bind' => $this,
			'entries' => $this->_helper->getPosts(),
			'templates' => $this->_helper->getTemplates(),
			'template_variables' => $this->_helper->getVariables($instance['template']),
			'page_variables' => array(),
			'partials' => array(),
		);

		// Build up partials. Partials - small layouts of widget form,
		// that can be loaded or overriden dynamically, f.e. by ajax.
		// At this moment only one partial can be used.
		$layout = 'default-widget-admin-variables-area'; // @todo вынести в конфиг
		$layoutPath = $this->_helper->getTemplatePath($layout);

		$params['partials']['template_variables'] = $this->_helper->renderTemplate($params, $layoutPath);

		if(isset($instance['page_variables'])) {
			$params['page_variables'] = $this->_helper->getPageVariables($instance['page_variables']);
		}

		// Here you can filter params
		$params = apply_filters('tpc_get_admin_params',	$params);

		// Render form
		$output = $this->_helper->renderTemplate($params, $templatePath);

		echo $output;
	}

	/**
	 * Private method for render widget's layout
	 * with pre-rendered template. Returns html-code of widget
	 * 
	 * @param array $data 
	 * @return string
	 */
	private function renderWidget($data) {
		$templatePath = $this->_config->get('system.template-directories.plugin');
		
		ob_start();
		set_query_var('widget', $data);

		load_template($templatePath . 'default-widget-client.php', false); // @todo вынести в конфиг

		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}
}