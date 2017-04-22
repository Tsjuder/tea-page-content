<?php

namespace TeaPageContent\App\Notices;

use TeaPageContent\App\Config;

class Manager {
    private $Config = null;

    public function __construct(Config\Repository $Config) {
        $this->Config = $Config;
    }

    public function update_deprecated_notice_option() {
        $version = $this->Config->get('system.versions.plugin');

        if(!get_option('tpc_deprecated_notice')) {
            add_option('tpc_deprecated_notice', $version, '', 'no');
        } else {
            update_option('tpc_deprecated_notice', $version, 'no');
        }
    }

    public function display_deprecated_notice() {
        $message = __('Thanks for update! We recommend you check out the <a href="https://wordpress.org/plugins/tea-page-content/changelog/">changelog</a>. <b>This notice disappear after closing.</b>');
        $content = '<div id="tpc-deprecated-notice" class="updated notice tpc-deprecated-notice is-dismissible"><p>' . $message . '</p></div>';

        echo $content;
    }
}