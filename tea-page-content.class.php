<?php
/**
 * @package Tea Page Content
 * @version 1.2.2
 */

class TeaPageContent {
	private $_helper;
	private $_config;

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

		add_action('init', array($this, 'updateSettings'));

		// Modify Admin page
		add_action('admin_footer-widgets.php', array($this, 'addPageVariablesModal'));
		add_action('admin_footer-edit.php', array($this, 'addPageVariablesModal'));
		add_action('admin_footer-post.php', array($this, 'addPageVariablesModal'));
		add_action('admin_footer-post-new.php', array($this, 'addPageVariablesModal'));

		add_action('admin_footer-edit.php', array($this, 'addInsertShortcodeModal'));
		add_action('admin_footer-post.php', array($this, 'addInsertShortcodeModal'));
		add_action('admin_footer-post-new.php', array($this, 'addInsertShortcodeModal'));

		// Includes all css, js, etc.
		add_action('wp_enqueue_scripts', array($this, 'includeAssets'), 100, 1);
		add_action('admin_enqueue_scripts', array($this, 'includeAdminAssets'), 100, 1);

		// Set Callbacks for ajax actions
		add_action('wp_ajax_get_template_variables', array($this, 'getTemplateVariablesCallback'));
		add_action('wp_ajax_generate_shortcode', array($this, 'generateShortcode'));

		add_action('wp_ajax_set_notice_seen', array($this, 'setNoticeSeenCallback'));

		add_action('media_buttons', array($this, 'add_my_media_button'), 1000);

		add_action('admin_menu', array($this, 'addMenu'), 100);

		add_filter('plugin_row_meta', array($this, 'addPluginMetaLinks'), 100, 2);


		// Create new helper instance
		$this->_helper = new TeaPageContent_Helper;

		// Gets instance of the config class
		$this->_config = TeaPageContent_Config::getInstance();


		// At first time notice user about possible migration
		if
			(
				($last_version = get_option('tpc_deprecated_notice'))
				&&
				$last_version !== $this->_config->get('system.versions.plugin')
			) 
		{
			add_action('admin_notices', array($this, 'displayDeprecatedNotice'));
		}
	}

	/**
	 * Add meta-link in plugin description on plugin list page.
	 * 
	 * @param array $links 
	 * @param string $file 
	 * @return array
	 */
	public function addPluginMetaLinks($links, $file) {
		if($file == plugin_basename(TEA_PAGE_CONTENT_FILE)) {
			$links[] = '<a href="options-general.php?page=tea-page-content">' . __('Settings', 'tea-page-content') . '</a>';
		}

		return $links;
	}

	/**
	 * Add button for inserting shortcode above text editor on admin pages.
	 * 
	 * @return void
	 */
	public function add_my_media_button() {
		$mask = '<a href="#" id="tpc-insert-shortcode" data-modal="tpc-call-shortcode-modal" data-button="insert" class="button tpc-button tpc-call-modal-button">%s</a>';
    	echo sprintf($mask, __('Tea Page Content Shortcode', 'tea-page-content'));
	}

	/**
	 * Add sub-menu for Options level.
	 * 
	 * @return void
	 */
	public function addMenu() {
		add_submenu_page('options-general.php', __('Tea Page Content - Settings', 'tea-page-content'), __('Tea Page Content', 'tea-page-content'), 'edit_dashboard', 'tea-page-content', array($this, 'renderSettingsPage'));
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
	 * into an admin page.
	 * 
	 * @param string $hook Current page of admin side
	 * @return void
	 */
	public function includeAdminAssets($hook) {
		$url = plugins_url('/assets', TEA_PAGE_CONTENT_FILE);

		if($hook === 'post.php' || $hook === 'post-new.php' || $hook === 'edit.php' || $hook === 'settings_page_tea-page-content') {
			
			wp_enqueue_script(
				'tea-page-content-js-api',
				$url . '/js/tea-page-content-api.js',
				array('jquery', 'jquery-ui-dialog'),
				$this->_config->get('system.versions.scripts'),
				true
			);

			wp_enqueue_script(
				'tea-page-content-js',
				$url . '/js/tea-page-content-admin.js',
				array('jquery', 'jquery-ui-dialog'),
				$this->_config->get('system.versions.scripts'),
				true
			);

			wp_enqueue_style(
				'tea-page-content-css',
				$url . '/css/tea-page-content-admin.css',
				array(),
				$this->_config->get('system.versions.styles'),
				'all'
			);

		} elseif($hook === 'widgets.php') {
			wp_enqueue_media();

			wp_enqueue_script(
				'tea-page-content-js-api',
				$url . '/js/tea-page-content-api.js',
				array('jquery', 'jquery-ui-dialog'),
				$this->_config->get('system.versions.scripts'),
				true
			);

			wp_enqueue_script(
				'tea-page-content-js',
				$url . '/js/tea-page-content-admin.js',
				array('jquery', 'jquery-ui-dialog'),
				$this->_config->get('system.versions.scripts'),
				true
			);
		
			wp_enqueue_style(
				'tea-page-content-css',
				$url . '/css/tea-page-content-admin.css',
				array(),
				$this->_config->get('system.versions.styles'),
				'all'
			);
		}

		if
			(
				($last_version = get_option('tpc_deprecated_notice'))
				&&
				$last_version !== $this->_config->get('system.versions.plugin')
			) 
		{
			wp_enqueue_script(
				'tea-page-content-notices-js',
				$url . '/js/tea-page-content-admin-notices.js',
				array('jquery'),
				$this->_config->get('system.versions.scripts'),
				true
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
	
		if($this->_config->get_current('system.settings.include-css')) {
			wp_enqueue_style(
				'tea-page-content',
				$url . '/css/tea-page-content-main.css',
				array(),
				$this->_config->get('system.versions.styles'),
				'all'
			);
		}
	}

	/**
	 * Callback for AJAX-action. Generates and echoes shortcode for editor.
	 * 
	 * @return void
	 */
	public function generateShortcode() {
		$prepared_data = array();

		$data = $_POST['data'];
		$data = explode('&', $data);

		foreach ($data as $index => $pair) {
			$pair = explode('=', $pair);

			if(!trim($pair[1])) {
				continue;
			}

			$keys = preg_split('/[\[\]]+/', urldecode($pair[0]), -1, PREG_SPLIT_NO_EMPTY);
			
			// $keys[0] is property name (order, page_variables, etc.)
			// $keys[1] is a page id (as usual)
			// So, if we haven't property in prepared data, set it up
			if(!array_key_exists($keys[0], $prepared_data)) {
				
				if(isset($keys[1])) {
					// page variables, set it
					$prepared_data[$keys[0]] = array(
						$keys[1] => $pair[1]
					);
				} else {
					// just post id, set it too
					$prepared_data[$keys[0]] = array(
						$pair[1]
					);
				}

			// But, if we have it already and post_id is not in $prepared_data, it means, we set up page variables
			} elseif(isset($keys[1]) && !array_key_exists($keys[1], $prepared_data[$keys[0]])) {

				// page variables in raw format stored in $pair array
				$prepared_data[$keys[0]][$keys[1]] = $pair[1];

			// And finally, if we haven't property and $keys[1] isn't set...
			} else {

				// ...it means, this is just post id that we need set separately
				// $pair[1] in these times can be just post_id integer
				// so $keys[0] now is `posts`
				$prepared_data[$keys[0]][] = $pair[1];
				
			}
		}

		$template = 'default'; // @todo через конфиг
		if(isset($prepared_data['template'])) {
			$template = $prepared_data['template'][0];
		}

		$shortcodes = array(
			'main' => array(),
		);

		$shortcode_defaults = $this->_config->get('defaults.shortcode');
		$page_variables = $this->_config->get('defaults.page-variables');
		$template_variables = $this->_helper->getVariables($template);

		// @todo make dis shit dry {1}
		if(isset($prepared_data['page_variables']) && isset($prepared_data['posts'])) {

			$last_id = null;

			foreach ($prepared_data['posts'] as $post_id) {
				if(isset($prepared_data['page_variables'][$post_id])) {
					$current_page_variables = $this->_helper->decodePageVariables(urldecode($prepared_data['page_variables'][$post_id]), $post_id, false);

					$shortcodes[$post_id] = array_merge(array(
						'posts' => $post_id,
					), $current_page_variables);

					$last_id = null;
				} else {
					if(is_null($last_id)) {
						$last_id = $post_id;
					}

					if(isset($shortcodes[$last_id]['posts'])) {
						$shortcodes[$last_id]['posts'] .= ', ' . $post_id;
					} else {
						$shortcodes[$last_id]['posts'] = $post_id;
					}
				}
			}

			unset($prepared_data['posts']);
			unset($prepared_data['page_variables']);
			unset($last_id);
		}

		foreach ($prepared_data as $param => $value) {
			if($param === 'posts') {
				$shortcodes['main'][$param] = implode(',', $value);
			} else {
				$shortcodes['main'][$param] = $value;
			}
		}

		// Build shortcodes up
		$output = array();
		$is_main_open = false;
		foreach ($shortcodes as $key => $attrs) {
			if($key === 'main') { // main shortcode

				$output[] = '[tea_page_content';

				foreach ($attrs as $attr_name => $attr_value) {
					if(is_array($attr_value)) {
						$attr_value = implode(',', $attr_value);
					}

					$output[] = ' '. $attr_name . '="'. urldecode(htmlspecialchars($attr_value)).'"';
				}

				if(count($shortcodes) > 1) {	
					$is_main_open = true;
				} else {
					$output[] = "/";
				}

				$output[] = "]\r\n";

			} else { // key is post_id, inner shortcode

				$output[] = '[tea_page_content';

				foreach ($attrs as $attr_name => $attr_value) {
					if(is_array($attr_value)) {
						$attr_value = implode(',', $attr_value);
					}

					$output[] = ' '. $attr_name . '="'.urldecode(htmlspecialchars($attr_value)).'"';
				}

				$output[] = "/]\r\n";

			}
		}

		if($is_main_open) {
			$output[] = "[/tea_page_content]";
		}

		echo implode('', $output);

		wp_die();
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
		$mask = $_POST['mask']; // @todo прекратить передавать это в ajax, вынести в конфиг
		// также потребуется поправить шаблоны (убрать этот параметр)

		$layout = 'default-widget-admin-variables-area'; // @todo вынести в конфиг

		$layoutPath = $this->_helper->getTemplatePath($layout);
		$variables = $this->_helper->getVariables($template);

		echo $this->_helper->renderTemplate(array(
			'template_variables' => $variables,
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
		$this->_helper->updateDeprecatedNoticeOption();
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
		$message = __('Thanks for update! We recommend you check out the <a href="https://wordpress.org/plugins/tea-page-content/changelog/">changelog</a>. <b>This notice disappear after closing.</b>');
		$content = '<div id="tpc-deprecated-notice" class="error notice tpc-deprecated-notice is-dismissible"><p>' . $message . '</p></div>';

		echo $content;
	}

	/**
	 * Render settings page.
	 * 
	 * @return void
	 */
	public function renderSettingsPage() {
		$params = array(
			'settings' => $this->_helper->getMappedSettings(),
		);

		$template = 'default-settings-page'; // @todo via config

		$templatePath = $this->_helper->getTemplatePath($template);

		echo $this->_helper->renderTemplate($params, $templatePath);
	}

	/**
	 * Render and display empty modal wrapper for JQuery UI Dialog.
	 * This wrapper will be used, mainly, in page level options.
	 * 
	 * @return void
	 */
	public function addPageVariablesModal() {
		$template = 'default-widget-admin-dialog-page-variables'; // @todo через конфиг

		$params = array(
			'page_variables' => $this->_config->get('defaults.page-variables')
		);

		if($templatePath = $this->_helper->getTemplatePath($template)) {
			$content = $this->_helper->renderTemplate($params, $templatePath);

			echo $content;
		}
	}

	/**
	 * Print in footer modal window html for inserting shortcode
	 * 
	 * @return void
	 */
	public function addInsertShortcodeModal() {
		$template = 'default-widget-admin-dialog-insert-shortcode'; // @todo через конфиг

		$template = apply_filters('tpc_get_admin_template_path', $template);

		// Gets a path to the template
		$templatePath = $this->_helper->getTemplatePath($template);

		// Specify a params for template
		$params = array(
			'instance' => $this->_config->get('defaults.shortcode', 'caller'), // @todo add deprecated `id` to exclude
			'entries' => $this->_helper->getPosts(),
			'templates' => $this->_helper->getTemplates(),
			'template_variables' => $this->_helper->getVariables('default'), // @todo через конфиг
			'page_variables' => array(),
			'partials' => array(),
			'mask' => '{mask}', // @todo вынести в конфиг
		);

		// Build up partials. Partials - small layouts of widget form,
		// that can be loaded or overriden dynamically, f.e. by ajax.
		// At this moment only one partial can be used.
		$layout = 'default-widget-admin-variables-area'; // @todo вынести в конфиг
		$layoutPath = $this->_helper->getTemplatePath($layout);

		$params['partials']['template_variables'] = $this->_helper->renderTemplate($params, $layoutPath);

		// Here you can filter params
		$params = apply_filters('tpc_get_admin_params',	$params);

		$content = $this->_helper->renderTemplate($params, $templatePath);

		echo $content;
	}

	/**
	 * Update settings if POST is not empty
	 * 
	 * @return void
	 */
	public function updateSettings() {
		if(!is_admin() || empty($_POST) || !isset($_POST['tpc_settings_update'])) {
			return;
		}

		unset($_POST['tpc_settings_update']);

		foreach ($_POST as $setting_name => $setting_value) {
			if
				(
					strpos($setting_name, 'tpc_') === false // @todo make dis shit dry {4}
					||
					preg_match('/[^\w-]/', $setting_name)
					||
					!is_scalar($setting_value)
				)
			{
				continue;
			}

			$config_path = $this->_helper->convertSettingToConfigPath($setting_name);

			$initial = $this->_config->get_default($config_path);

			if(is_null(get_option($setting_name, null))) {
				add_option($setting_name, $setting_value, '', 'no');
			} elseif($initial == $setting_value) {
				delete_option($setting_name);
			} else {
				update_option($setting_name, $setting_value, 'no');
			}
		}
	}
}