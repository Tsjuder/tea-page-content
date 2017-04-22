<?php

namespace TeaPageContent\App\Main;

use TeaPageContent\App\Config;
use TeaPageContent\App\Notices;

class AdminNotices {
    private $Config = null;
    private $NoticeManager = null;
    
    public function __construct(Config\Repository $Config, Notices\Manager $NoticeManager) {
        $this->Config = $Config;
        $this->NoticeManager = $NoticeManager;
    }

    public function is_last_plugin_version_older_than_current() {
        $last_version = get_option('tpc_deprecated_notice');
        $current_version = $this->Config->get('system.versions.plugin');

        if($last_version && $last_version !== $current_version) {
            return true;
        }

        return false;
    }

    public function display_deprecated_notice() {
        $this->NoticeManager->display_deprecated_notice();
    }
}