<?php
namespace Ank91\WP_Screen_Options;

class WP_Screen_Options_Admin
{
    const PLUGIN_SLUG = 'screen_options_demo';
    const AJAX_ACTION = 'wpsco_ajax';
    const PLUGIN_OPTION_NAME = 'wpsco_options';

    function __construct()
    {
        /* To save default options upon activation*/
        register_activation_hook(WPSCO_BASE_FILE, array($this, 'do_upon_plugin_activation'));

        /* Add settings link under admin->settings menu */
        add_action('admin_menu', array($this, 'add_to_settings_menu'));

        /* Add settings link to plugin list page */
        add_filter('plugin_action_links_' . WPSCO_BASE_FILE, array($this, 'add_plugin_actions_links'), 10, 2);

        /* Add custom screen options panel wp v3.0+*/
        add_filter('screen_settings', array($this, 'print_screen_options'), 10, 2);

        /* Register ajax saving function */
        add_action('wp_ajax_' . self::AJAX_ACTION, array(&$this, 'save_screen_options'));

    }

    /*
    * Save default settings upon plugin activation
    */
    function do_upon_plugin_activation()
    {

        //if db options not exists then update with defaults
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
    function add_to_settings_menu()
    {
        $page_hook_suffix = add_submenu_page('options-general.php', 'WP Screen Options', 'Screen Options', 'manage_options', self::PLUGIN_SLUG, array($this, 'load_options_page'));
        /* We can load additional css/js to our option page here */
        add_action('admin_print_scripts-' . $page_hook_suffix, array($this, 'print_admin_js'));

    }

    /**
     * Function will print our option page form
     */
    function load_options_page()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $this->load_view('options_page.php');

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


    /**
     * Print html for screen options
     * @source http://www.w-shadow.com/blog/2010/06/29/adding-stuff-to-wordpress-screen-options/
     *
     * @param $current
     * @param $screen
     * @return mixed
     */
    function print_screen_options($current, $screen)
    {

        if (strpos($screen->id, self::PLUGIN_SLUG) !== false) {
            ob_start();
            $this->load_view('screen_options.php');
            ?>
            <?php
            $current .= ob_get_clean();
        }
        return $current;
    }

    /**
     * Save screen options ajax request back to database
     */
    function save_screen_options()
    {
        if (isset($_POST['action']) && $_POST['action'] === self::AJAX_ACTION) {
            /* WP inbuilt form security check */
            check_ajax_referer(self::AJAX_ACTION, '_wpnonce-wpsco_meta_form');

            $inputs = $_POST['wpsco_options'];

            $secure = array();
            $secure['check_box_1'] = (isset($inputs['check_box_1'])) ? 1 : 0;
            $secure['check_box_2'] = (isset($inputs['check_box_2'])) ? 1 : 0;
            $secure['number_1'] = absint($inputs['number_1']);

            update_option(self::PLUGIN_OPTION_NAME, $secure);

            die('1');
        }
    }

    /**
     * Loads a view (template file)
     * @param $file String File name with ext
     * @throws \Exception
     */
    private function load_view($file)
    {
        $file_path = __DIR__ . '/views/' . $file;

        if (is_readable($file_path)) {
            require($file_path);
        } else {
            throw new \Exception("Unable to load settings page, Template File not found, (v" . WPSCO_PLUGIN_VER . ")");
        }
    }

    /**
     * Print option page javascript
     */
    function print_admin_js()
    {
        $is_min = (WP_DEBUG == 1) ? '' : '.min';
        wp_enqueue_script('wpsco-admin', plugins_url("/js/screen-options" . $is_min . ".js", __FILE__), array('jquery'), WPSCO_PLUGIN_VER, false);
    }
}