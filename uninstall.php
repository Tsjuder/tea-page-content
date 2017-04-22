<?php

if(!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

$settings_map = include('config.map.php');

if(is_array($settings_map) && !empty($settings_map)) {
    foreach ($settings_map as $setting_name => $setting_params) {
        $option = 'tpc_' . str_replace('.', '__', $setting_name);

        if(!is_null(get_option($option, null))) {
            delete_option($option);
        }
    }
}

delete_option('tpc_deprecated_notice');