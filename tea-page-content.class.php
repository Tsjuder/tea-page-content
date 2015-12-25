<?php
/**
 * @package Tea Page Content
 * @version 1.0.0
 */

class TeaPageContent {
	private static $_instance;

	/**
	 * Private constructor
	 * 
	 * @return void
	 */
	private function __construct() {}
    private function __clone() {
    	return self::$_instance;
    }
    private function __wakeup() {
    	return self::$_instance;
    }

    /**
     * Enter point to the a singleton.
     * 
     * @return object
     */
	public static function getInstance() {
		if(is_null(self::$_instance)) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}

	/**
	 * Run this plugin, load textdomain, adds filters & actions
	 * 
	 * @return void
	 */
	public function initialize() {
		if(is_null(self::$_instance)) {
			self::getInstance();
		}

		// load textdomain
		load_plugin_textdomain('tea-page-content', false, TEA_PAGE_CONTENT_FOLDER . '/languages/');

		// Adds filters, actions, etc.
		add_action('init', array(self::$_instance, 'registerShortcodes'));
		add_action('widgets_init', array(self::$_instance, 'registerWidgets'));

		add_action('wp_enqueue_scripts', array(self::$_instance, 'includeAssets'), 100, 1);
		add_action('admin_enqueue_scripts', array(self::$_instance, 'includeAdminAssets'), 100, 1);

		add_filter('tpc_get_params', array(self::$_instance, 'flattenEntries'), 10, 1);

		// Includes all css, js, etc.

		

	}

	/**
	 * Register Tea Page Content widget
	 * 
	 * @return void
	 */
	public function registerWidgets() {
		register_widget('TeaPageContent_Widget');
	}

	public function registerShortcodes() {
		add_shortcode('tea_page_content', array('TeaPageContent_Shortcodes', 'tea_page_content'));
	}

	/**
	 * Adds all scripts, styles and media
	 * into an admin page. Actual only for widgets
	 * 
	 * @param string $hook Current page of admin side
	 * @return void
	 */
	public function includeAdminAssets($hook) {
		if($hook == 'widgets.php') {
			$url = plugins_url('/assets', TEA_PAGE_CONTENT_FILE);

			wp_enqueue_script(
				'tea-page-content-js',
				$url . '/js/tea-page-content-admin.js',
				array('jquery'),
				'1.0.0',
				true
			);
		
			wp_enqueue_style(
				'tea-page-content-css',
				$url . '/css/tea-page-content-admin.css',
				array(),
				'1.0.0',
				'all'
			);
		}
	}

	/**
	 * Adds all scripts, styles and media
	 * into a frontend part of site
	 * 
	 * @return void
	 */
	public function includeAssets() {
		$url = plugins_url('/assets', TEA_PAGE_CONTENT_FILE);

		/*wp_enqueue_script(
			'tea-page-content-js',
			$url . '/js/tea-page-content-main.js',
			array('jquery'),
			'1.0.0',
			true
		);*/
	
		wp_enqueue_style(
			'tea-page-content-css',
			$url . '/css/tea-page-content-main.css',
			array(),
			'1.0.1',
			'all'
		);
	}

	/**
	 * Filters entries from params of widget's frontend part
	 * Binded to the tpc_get_params hook
	 * 
	 * Here, this function flattens two-dimensional
	 * entries array into one-dimensional
	 * 
	 * @param array $params 
	 * @return array
	 */
	public function flattenEntries($params) {
		if(isset($params['entries']) && count($params['entries'])) {
			$entries = &$params['entries'];

			$entries = call_user_func_array('array_merge', $entries);
		}

		return $params;
	}
}