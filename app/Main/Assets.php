<?php

namespace TeaPageContent\App\Main;

use TeaPageContent\App\Config;

class Assets {
    private $Config = null;

    public function __construct(Config\Repository $Config) {
        $this->Config = $Config;
    }

    public function include_admin_assets($hook) {
        $url = plugins_url('/assets', \TeaPageContent\PLUGIN_FILE);

        if($hook === 'post.php' || $hook === 'post-new.php' || $hook === 'edit.php' || $hook === 'settings_page_tea-page-content') {
            
            wp_enqueue_script(
                'tea-page-content-js-api',
                $url . '/js/tea-page-content-api.js',
                array('jquery', 'jquery-ui-dialog', 'jquery-ui-spinner'),
                $this->Config->get('system.versions.scripts'),
                true
            );

            wp_enqueue_script(
                'tea-page-content-js',
                $url . '/js/tea-page-content-admin.js',
                array('jquery', 'jquery-ui-dialog', 'jquery-ui-spinner'),
                $this->Config->get('system.versions.scripts'),
                true
            );

            wp_enqueue_style(
                'tea-page-content-css',
                $url . '/css/tea-page-content-admin.css',
                array(),
                $this->Config->get('system.versions.styles'),
                'all'
            );

        } elseif($hook === 'widgets.php') {
            wp_enqueue_media();

            wp_enqueue_script(
                'tea-page-content-js-api',
                $url . '/js/tea-page-content-api.js',
                array('jquery', 'jquery-ui-dialog', 'jquery-ui-spinner'),
                $this->Config->get('system.versions.scripts'),
                true
            );

            wp_enqueue_script(
                'tea-page-content-js',
                $url . '/js/tea-page-content-admin.js',
                array('jquery', 'jquery-ui-dialog', 'jquery-ui-spinner'),
                $this->Config->get('system.versions.scripts'),
                true
            );
        
            wp_enqueue_style(
                'tea-page-content-css',
                $url . '/css/tea-page-content-admin.css',
                array(),
                $this->Config->get('system.versions.styles'),
                'all'
            );
        }

        if
            (
                ($last_version = get_option('tpc_deprecated_notice'))
                &&
                $last_version !== $this->Config->get('system.versions.plugin')
            ) 
        {
            wp_enqueue_script(
                'tea-page-content-notices-js',
                $url . '/js/tea-page-content-admin-notices.js',
                array('jquery'),
                $this->Config->get('system.versions.scripts'),
                true
            );
        }
    }

    public function include_client_assets() {
        $url = plugins_url('/assets', \TeaPageContent\PLUGIN_FILE);
    
        if($this->Config->get_current('system.settings.include-css')) {
            wp_enqueue_style(
                'tea-page-content',
                $url . '/css/tea-page-content-main.css',
                array(),
                $this->Config->get('system.versions.styles'),
                'all'
            );
        }
    }
}