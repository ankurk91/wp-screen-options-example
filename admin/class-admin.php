<?php
namespace Ank91\WP_Screen_Options;

class WP_Screen_Options_Admin
{
    const PLUGIN_SLUG = 'screen_options';

    function __construct()
    {
        /* Add settings link under admin->settings menu */
        add_action('admin_menu', array($this, 'add_to_settings_menu'));

        /* Add settings link to plugin list page */
        add_filter('plugin_action_links_' . WPSCOP_BASE_FILE, array($this, 'add_plugin_actions_links'), 10, 2);

    }

    /**
     * Adds link to Plugin Option page and do related stuff
     */
    function add_to_settings_menu()
    {
        $page_hook_suffix = add_submenu_page('options-general.php', 'WP Screen Options', 'Screen Options', 'manage_options', self::PLUGIN_SLUG, array($this, 'load_options_page'));

    }

    /**
     * Function will print our option page form
     */
    function load_options_page()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $file_path = __DIR__ . '/views/options_page.php';

        if (file_exists($file_path)) {
            require($file_path);
        } else {
            throw new \Exception("Unable to load settings page, Template File not found, (v" . WPSCOP_PLUGIN_VER . ")");
        }

    }

    /**
     * Adds a 'Settings' link for this plugin on plugin listing page
     *
     * @param $links
     * @return array  Links array
     */
    function add_plugin_actions_links($links)
    {

        if (current_user_can('manage_options')) {
            $build_url = add_query_arg('page', self::PLUGIN_SLUG, 'options-general.php');
            array_unshift(
                $links,
                sprintf('<a href="%s">%s</a>', $build_url, __('Settings'))
            );
        }

        return $links;
    }
}