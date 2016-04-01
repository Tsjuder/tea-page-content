<?php
/**
 * @package Tea Page Content
 * @version 1.1.0
 */

class TeaPageContent {
	private $_helper;

	/**
	 * Constructor
	 * 
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Run this plugin, load textdomain, adds filters & actions
	 * 
	 * @return void
	 */
	public function initialize() {
		// Load textdomain
		load_plugin_textdomain('tea-page-content', false, TEA_PAGE_CONTENT_FOLDER . '/languages/');

		// Adds filters, actions, etc.
		add_action('init', array($this, 'registerShortcodes'));
		add_action('widgets_init', array($this, 'registerWidgets'));

		// Tea Page Content filters

		// Includes all css, js, etc.
		add_action('wp_enqueue_scripts', array($this, 'includeAssets'), 100, 1);
		add_action('admin_enqueue_scripts', array($this, 'includeAdminAssets'), 100, 1);

		add_action('wp_ajax_get_template_variables', array($this, 'getTemplateVariablesCallback'));

		add_action('wp_ajax_set_notice_seen', array($this, 'setNoticeSeenCallback'));

		// At first time notice user about possible migration
		if(!get_option('tpc_deprecated_notice')) {
			add_action('admin_notices', array($this, 'displayDeprecatedNotice'));
		}

		$this->_helper = new TeaPageContent_Helper;
	}

	/**
	 * Register Tea Page Content widget
	 * 
	 * @return void
	 */
	public function registerWidgets() {
		register_widget('TeaPageContent_Widget');
	}

	/**
	 * Register Tea Page Content shortcode
	 * 
	 * @return void
	 */
	public function registerShortcodes() {
		add_shortcode('tea_page_content', array('TeaPageContent_Shortcode', 'tea_page_content'));
	}

	/**
	 * Adds all scripts, styles and media
	 * into an admin page. Actual only for widgets
	 * 
	 * @param string $hook Current page of admin side
	 * @return void
	 */
	public function includeAdminAssets($hook) {
		$url = plugins_url('/assets', TEA_PAGE_CONTENT_FILE);

		if($hook == 'widgets.php') {
			wp_enqueue_script(
				'tea-page-content-js',
				$url . '/js/tea-page-content-admin.js',
				array('jquery'),
				TeaPageContent_Config::get('system.versions.scripts'),
				true
			);
		
			wp_enqueue_style(
				'tea-page-content-css',
				$url . '/css/tea-page-content-admin.css',
				array(),
				TeaPageContent_Config::get('system.versions.styles'),
				'all'
			);
		}

		wp_enqueue_script(
			'tea-page-content-notices-js',
			$url . '/js/tea-page-content-admin-notices.js',
			array('jquery'),
			TeaPageContent_Config::get('system.versions.scripts'),
			true
		);
	}

	/**
	 * Adds all scripts, styles and media
	 * into a frontend part of site
	 * 
	 * @return void
	 */
	public function includeAssets() {
		$url = plugins_url('/assets', TEA_PAGE_CONTENT_FILE);
	
		wp_enqueue_style(
			'tea-page-content-css',
			$url . '/css/tea-page-content-main.css',
			array(),
			TeaPageContent_Config::get('system.versions.styles'),
			'all'
		);
	}

	/**
	 * Callback for AJAX-action. Gets and returns template variables
	 * by passed template and mask. Mask is unique name of current instance of widget.
	 * This function can be used only in ajax, in other cases it returns nothing.
	 * 
	 * @return void
	 */
	public function getTemplateVariablesCallback() {
		$template = $_POST['template'];
		$mask = $_POST['mask'];

		$layout = 'default-widget-admin-variables-area';

		$layoutPath = $this->_helper->getTemplatePath($layout);
		$variables = $this->_helper->getVariables($template);

		echo $this->_helper->renderTemplate(array(
			'variables' => $variables,
			'mask' => $mask
		), $layoutPath);

		wp_die();
	}

	/**
	 * Callback for AJAX-action. Fires when user closes deprecated notice.
	 * Set up unique option with current version of this plugin. 
	 * 
	 * This option will be deleted after uninstall.
	 * 
	 * @return void
	 */
	public function setNoticeSeenCallback() {
		$version = $_POST['version']; // @todo get version from config

		if(!get_option('tpc_deprecated_notice')) {
			add_option('tpc_deprecated_notice', $version, '', 'no');
		}
	}

	/**
	 * Created and print deprecated notice. Ugly, but simple.
	 * Will be recommend check out changelog at wordpress.org
	 * 
	 * @todo do something with html in my php
	 * 
	 * @return void
	 */
	public function displayDeprecatedNotice() {
		$message = __('Warning! Since Tea Page Content 1.1 some parameters is <b>deprecated</b>, and will be <b>deleted</b> in the next release. We strongly recommend you check out the <a href="https://wordpress.org/plugins/tea-page-content/changelog/">changelog</a>. <b>This notice appears only once!</b>');
		$content = '<div id="tpc-deprecated-notice" class="error notice tpc-deprecated-notice is-dismissible"><p>' . $message . '</p></div>';

		echo $content;
	}
}