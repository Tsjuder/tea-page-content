<?php
/*
Plugin Name: Tea Page Content
Plugin URI: http://tsjuder.github.io/tea-page-content
Description: This plugin allows create blocks with content of any post, and customize look of blocks via templates. Widget, shortcode, all post types is supported.
Version: 1.3.1
Text Domain: tea-page-content
Domain Path: /languages/
Author: Raymond Costner
Author URI: https://github.com/Tsjuder
GitHub Plugin URI: https://github.com/Tsjuder/tea-page-content
GitHub Branch: master

License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2016-2017 Raymond Costner
*/

namespace TeaPageContent;

use TeaPageContent\App\DependencyInjector;
use TeaPageContent\App\Main;

define(__NAMESPACE__ . '\\MIN_PHP_REQUIRED', 5.6);
define(__NAMESPACE__ . '\\PLUGIN_FILE', __FILE__);
define(__NAMESPACE__ . '\\PLUGIN_PATH', dirname(PLUGIN_FILE));
define(__NAMESPACE__ . '\\PLUGIN_FOLDER', basename(PLUGIN_PATH));

// Functions
function get_major_php_version() {
    $php_version = explode('.', PHP_VERSION);
    $php_version = (float)implode('.', array_splice($php_version, 0, 2));

    return $php_version;
}

function run() {
    spl_autoload_register(__NAMESPACE__ . '\\autoload');

    $DependencyInjector = new DependencyInjector;
    $Main = new Main($DependencyInjector);

    spl_autoload_unregister(__NAMESPACE__ . '\\autoload');

    add_action('plugins_loaded', array($Main, 'init'));
}

function autoload($class_name) {
    $parsed_class_name = explode('\\', $class_name);

    if($parsed_class_name[0] === __NAMESPACE__) {
        unset($parsed_class_name[0]);
    }

    include PLUGIN_PATH . '/' . implode('/', $parsed_class_name) . '.php';
}

// Check minimal PHP installed
if(get_major_php_version() < MIN_PHP_REQUIRED) {
    if(is_admin()) {
        add_action('admin_notices', function() {
            $message = __('<b>Important!</b> Your version of PHP is less than <b>%s</b>! Tea Page Content plugin will <b>NOT</b> run. Upgrade your PHP to version <b>%s</b> or higher. This is a minimal technical requirements.');
            $content = '<div class="error notice"><p>' . $message . '</p></div>';

            echo sprintf($content, MIN_PHP_REQUIRED, MIN_PHP_REQUIRED);
        });
    }

    return;
}

// Impossible to load via autoload
require_once(PLUGIN_PATH . '/App/Modules/Widget.php');

// Start the app
run();