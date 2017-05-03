<?php

namespace Ankur\Plugins\WP_Screen_Options;

/**
 * Plugin Name: WP Screen Options
 * Plugin URI: https://github.com/ankurk91/wp-screen-options-example
 * Description: Demo plugin to demonstrate screen options
 * Version: 1.0.1
 * Author: Ankur Kumar
 * Author URI: http://ankurk91.github.io/
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 * Text Domain: wp-screen-options-demo
 * Domain Path: /languages
 */


define('WPSCO_PLUGIN_VER', '1.0.1');
define('WPSCO_BASE_FILE', __FILE__);

/**
 * Calling required files
 */
if (is_admin()) {
    require_once __DIR__ . '/inc/class-admin.php';
    new Admin();
} else {
    require_once __DIR__ . '/inc/class-frontend.php';
    new Frontend();
}
