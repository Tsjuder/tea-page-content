<?php

namespace TeaPageContent\App\Main;

/**
 * Hooks for all UI elements in admin side.
 */
class AdminElements {
    private $Settings = null;

    public function __construct(Settings $Settings) {
        $this->Settings = $Settings;
    }

    public function add_plugin_metalinks($links, $file) {
        if($file === plugin_basename(\TeaPageContent\PLUGIN_FILE)) {
            $links[] = '<a href="options-general.php?page=tea-page-content">' . __('Settings', 'tea-page-content') . '</a>';
        }

        return $links;
    }

    public function add_media_button_for_shortcode_insert() {
        $mask = '<a href="#" id="tpc-insert-shortcode" data-modal="tpc-call-shortcode-modal" data-button="insert" class="button tpc-button tpc-call-modal-button">%s</a>';
        echo sprintf($mask, __('Tea Page Content Shortcode', 'tea-page-content'));
    }

    public function add_menu() {
        add_submenu_page('options-general.php', __('Tea Page Content - Settings', 'tea-page-content'), __('Tea Page Content', 'tea-page-content'), 'edit_dashboard', 'tea-page-content', [$this->Settings, 'render_page']);
    }
}