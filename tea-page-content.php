<?php
/*
Plugin Name: Tea Page Content
Plugin URI: http://tsjuder.github.io/tea-page-content
Description: This plugin allows create most flexible blocks with any content of any page via widgets or shortcodes, and manage layouts of blocks with help of templates.
Version: 1.0.0
Text Domain: tea-page-content
Domain Path: /languages/
Author: Danil Kosterin
Author URI: https://github.com/Tsjuder
GitHub Plugin URI: https://github.com/Tsjuder/tea-page-content
GitHub Branch: master

License: GPLv2 or later
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

Copyright 2015 Danil Kosterin
*/

// Necessary constants
define('TEA_PAGE_CONTENT_FILE', __FILE__);
define('TEA_PAGE_CONTENT_PATH', dirname(__FILE__));
define('TEA_PAGE_CONTENT_FOLDER', basename(TEA_PAGE_CONTENT_PATH));

// Includes a core
require_once(TEA_PAGE_CONTENT_PATH . '/tea-page-content.class.php');
require_once(TEA_PAGE_CONTENT_PATH . '/helpers/helper.php');

// Includes a widget interface
require_once(TEA_PAGE_CONTENT_PATH . '/widgets/tea-page-content.php');

// Includes a shortcodes
require_once(TEA_PAGE_CONTENT_PATH . '/shortcodes/tea-page-content.php');

// Gets an instance
$engine = TeaPageContent::getInstance();

add_action('plugins_loaded', array($engine, 'initialize'));