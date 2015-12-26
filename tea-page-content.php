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

License: MIT (http://directory.fsf.org/wiki/License:Expat)
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