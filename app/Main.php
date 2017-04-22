<?php

namespace TeaPageContent\App;

class Main {
    public function __construct(DependencyInjector $DependencyInjector) {
        $DependencyInjector->inject_dependencies_to($this);
    }

    public function init() {
        $this->init_language();

        // Shortcode and Widget
        $this->register_modules();

        // Settings update & Config filtering
        $this->add_init_hooks();

        // Menu, Settings page, buttons
        $this->add_admin_elements();

        $this->enqueue_assets();

        $this->add_modals_in_admin_pages();
        $this->add_ajax_callbacks();

        $this->add_deprecated_notice_if_required();
    }

    private function init_language() {
        load_plugin_textdomain('tea-page-content', false, \TeaPageContent\PLUGIN_FOLDER . '/languages/');
    }

    private function register_modules() {
        add_action('init', array($this->Registrator, 'register_shortcode'));
        add_action('widgets_init', array($this->Registrator, 'register_widget'));
    }

    private function add_init_hooks() {
        add_action('init', array($this->Settings, 'update'));
        add_action('init', array($this->ConfigHandler, 'handle'));
    }

    private function add_admin_elements() {
        add_action('media_buttons', array($this->AdminElements, 'add_media_button_for_shortcode_insert'), 1000);
        add_action('admin_menu', array($this->AdminElements, 'add_menu'), 100);
        add_filter('plugin_row_meta', array($this->AdminElements, 'add_plugin_metalinks'), 100, 2);
    }

    private function enqueue_assets() {
        add_action('wp_enqueue_scripts', array($this->Assets, 'include_client_assets'), 100, 1);
        add_action('admin_enqueue_scripts', array($this->Assets, 'include_admin_assets'), 100, 1);
    }

    private function add_modals_in_admin_pages() {
        add_action('admin_footer-widgets.php', array($this->Modals, 'add_page_variables_modal'));
        add_action('admin_footer-edit.php', array($this->Modals, 'add_page_variables_modal'));
        add_action('admin_footer-post.php', array($this->Modals, 'add_page_variables_modal'));
        add_action('admin_footer-post-new.php', array($this->Modals, 'add_page_variables_modal'));

        add_action('admin_footer-edit.php', array($this->Modals, 'add_insert_shortcode_modal'));
        add_action('admin_footer-post.php', array($this->Modals, 'add_insert_shortcode_modal'));
        add_action('admin_footer-post-new.php', array($this->Modals, 'add_insert_shortcode_modal'));
    }

    private function add_ajax_callbacks() {
        add_action('wp_ajax_generate_shortcode', array($this->Callbacks, 'generate_shortcode_callback'));
        add_action('wp_ajax_get_template_variables', array($this->Callbacks, 'get_template_variables_callback'));
        add_action('wp_ajax_set_notice_seen', array($this->Callbacks, 'set_notice_seen_callback'));
    }

    private function add_deprecated_notice_if_required() {
        if($this->AdminNotices->is_last_plugin_version_older_than_current()) {
           add_action('admin_notices', [$this->AdminNotices, 'display_deprecated_notice']); 
       }
    }
}