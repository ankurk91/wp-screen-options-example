<?php
namespace Ankur\Plugins\WP_Screen_Options;

/**
 * Class Admin
 * @package Ankur\Plugins\WP_Screen_Options
 */
class Admin
{
    /**
     * Define some constants
     */
    const PLUGIN_SLUG = 'screen_options_demo';
    const AJAX_ACTION = 'wpsco_ajax';
    const PLUGIN_OPTION_NAME = 'wpsco_options';

    public function __construct()
    {
        // To save default options upon activation
        register_activation_hook(plugin_basename(WPSCO_BASE_FILE), array($this, 'do_upon_plugin_activation'));

        // Add settings link under admin->settings menu
        add_action('admin_menu', array($this, 'add_to_settings_menu'));

        // Add settings link to plugin list page
        add_filter('plugin_action_links_' . plugin_basename(WPSCO_BASE_FILE), array($this, 'add_plugin_actions_links'), 10, 2);

        // Add custom screen options panel wp v3.0+
        add_filter('screen_settings', array($this, 'print_screen_options'), 10, 2);

        // Register ajax saving function
        add_action('wp_ajax_' . self::AJAX_ACTION, array(&$this, 'save_screen_options'));

    }

    /*
    * Save default settings upon plugin activation
    */
    public function do_upon_plugin_activation()
    {

        // if db options not exists then update with defaults
        if (get_option(self::PLUGIN_OPTION_NAME) == false) {
            update_option(self::PLUGIN_OPTION_NAME, $this->get_default_options());
        }

    }

    /**
     * Returns the default database options
     * @return array
     */
    private function get_default_options()
    {

        $default_options = array(
            'check_box_1' => 1,
            'check_box_2' => 1,
            'number_1' => 20,
        );
        return $default_options;
    }

    /**
     * Adds link to Plugin Option page and do related stuff
     */
    public function add_to_settings_menu()
    {
        $page_hook_suffix = add_submenu_page('options-general.php', 'WP Screen Options', 'WP Screen Options', 'manage_options', self::PLUGIN_SLUG, array($this, 'load_options_page'));
        // We can load additional css/js to our option page here
        add_action('admin_print_scripts-' . $page_hook_suffix, array($this, 'print_admin_assets'));

    }

    /**
     * Function will print our option page form
     */
    public function load_options_page()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $this->load_view('options-page.php');

    }

    /**
     * Adds a 'Settings' link for this plugin on plugin listing page
     *
     * @param $links
     * @return array  Links array
     */
    public function add_plugin_actions_links($links)
    {

        if (current_user_can('manage_options')) {
            $url = add_query_arg('page', self::PLUGIN_SLUG, 'options-general.php');
            array_unshift(
                $links,
                sprintf('<a href="%s">%s</a>', $url, __('Settings'))
            );
        }

        return $links;
    }


    /**
     * Print html for screen options
     * @link http://www.w-shadow.com/blog/2010/06/29/adding-stuff-to-wordpress-screen-options/
     *
     * @param $current
     * @param $screen
     * @return mixed
     */
    public function print_screen_options($current, $screen)
    {

        if (strpos($screen->id, self::PLUGIN_SLUG) !== false) {
            ob_start();
            $this->load_view('screen_options.php', $this->get_view_vars());
            ?>
            <?php
            $current .= ob_get_clean();
        }
        return $current;
    }

    private function get_view_vars()
    {
        return array(
            'db' => get_option(self::PLUGIN_OPTION_NAME),
            'ajax_action' => self::AJAX_ACTION
        );
    }

    /**
     * Save screen options ajax request back to database
     */
    public function save_screen_options()
    {
        // WP inbuilt form security check
        check_ajax_referer(self::AJAX_ACTION, '_wpnonce-wpsco_meta_form');

        $inputs = $_GET['wpsco_options'];

        $secure = array();
        $secure['check_box_1'] = isset($inputs['check_box_1']);
        $secure['check_box_2'] = isset($inputs['check_box_2']);
        $secure['number_1'] = absint($inputs['number_1']);

        update_option(self::PLUGIN_OPTION_NAME, $secure);

        wp_send_json_success();

    }

    /**
     * Loads a view (template file)
     * @param $file String File name with ext
     * @param $_vars array
     * @throws \Exception
     */
    private function load_view($file, $_vars = array())
    {
        $file_path = plugin_dir_path(WPSCO_BASE_FILE) . '/views/' . $file;

        if (is_readable($file_path)) {
            extract($_vars);
            unset($vars);
            require $file_path;
        } else {
            throw new \Exception("Unable to load settings page, Template file" . esc_html($file_path) . " not found");
        }
    }

    /**
     * Print option page javascript
     */
    public function print_admin_assets()
    {
        wp_enqueue_script('wpsco-admin', plugins_url("/js/screen-options.js", WPSCO_BASE_FILE), array('jquery'), WPSCO_PLUGIN_VER, false);
    }
}