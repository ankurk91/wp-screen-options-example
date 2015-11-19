<?php
namespace Ank91\WP_Screen_Options;
?><?php
/*
Plugin Name: WP Screen Options
Plugin URI: https://github.com/ank91/wp-screen-options
Description: Demo plugin to demonstrate screen options
Version: 1.0
Author: Ankur Kumar
Author URI: http://ank91.github.io/
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wp-screen-options
Domain Path: /languages
*/
?><?php

define('WPSCOP_PLUGIN_VER', '1.0.0');
define('WPSCOP_BASE_FILE', plugin_basename(__FILE__));

if (is_admin()) {
    require(__DIR__ . '/admin/class-admin.php');
    new WP_Screen_Options_Admin();
} else {
    require(__DIR__ . '/front-end/class-frontend.php');
    new WP_Screen_Options_Frontend();
}