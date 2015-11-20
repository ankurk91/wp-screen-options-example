<?php

/**
 * Uninstall file for this plugin
 * This file will be used to remove all traces of this plugin when uninstalled
 */


// If uninstall not called from WordPress do exit
if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN'))
    exit;


// Remove the database entry created by this plugin
delete_option('wpsco_options');

