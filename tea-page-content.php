<?php
/*
Plugin Name: Tea Page Widget & Content
Plugin URI: http://tsjuder.github.io/tea-page-content
Description: This plugin allows create blocks with content of any post, and customize look of blocks via templates. Widget, shortcode, all post types is supported.
Version: 1.2.1
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

Copyright 2016 Raymond Costner
*/

// Necessary constants
define('TEA_PAGE_CONTENT_FILE', __FILE__);
define('TEA_PAGE_CONTENT_PATH', dirname(__FILE__));
define('TEA_PAGE_CONTENT_FOLDER', basename(TEA_PAGE_CONTENT_PATH));

// Includes a core
require_once(TEA_PAGE_CONTENT_PATH . '/tea-page-content.class.php');

// Includes a classes
require_once(TEA_PAGE_CONTENT_PATH . '/classes/config.class.php');
require_once(TEA_PAGE_CONTENT_PATH . '/classes/helper.class.php');

// Includes a modules
require_once(TEA_PAGE_CONTENT_PATH . '/modules/shortcode.php');
require_once(TEA_PAGE_CONTENT_PATH . '/modules/widget.php');

// Gets an instance
$tpcEngine = new TeaPageContent;

add_action('plugins_loaded', array($tpcEngine, 'initialize'));