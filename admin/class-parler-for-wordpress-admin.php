<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Parler_For_Wordpress
 * @subpackage Parler_For_Wordpress/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Parler_For_Wordpress
 * @subpackage Parler_For_Wordpress/admin
 * @author     Your Name <email@example.com>
 */
class Parler_For_WordpressAdmin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Parler_For_WordpressLoader as all of the hooks are defined
         * in that particular class.
         *
         * The Parler_For_WordpressLoader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/parler-for-wordpress-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Parler_For_WordpressLoader as all of the hooks are defined
         * in that particular class.
         *
         * The Parler_For_WordpressLoader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/parler-for-wordpress-admin.js', array('jquery'), $this->version, false);

    }

    public function register_settings_page()
    {
        // Register Options
        $this->parler_register_settings();

        // Admin Menu
        add_action('admin_menu', 'parler_admin_add_page');
        function parler_admin_add_page()
        {
            add_options_page('Parler Admin Settings', 'Parler', 'manage_options', 'parler', 'parler_options_page');
        }

        // Settings Page
        function parler_options_page()
        {
            $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';

            ?>
            <div class="wrap">
                <h1>Parler Settings</h1>
                <h2 class="nav-tab-wrapper">
                    <a href="?page=parler&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">General</a>
                    <a href="?page=parler&tab=import" class="nav-tab <?php echo $active_tab == 'import' ? 'nav-tab-active' : ''; ?>">Import</a>
                </h2>
                <form method="post" action="options.php">
                    <?php settings_fields('parler-option-group'); ?>
                    <?php do_settings_sections('parler-option-group'); ?>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">Default Comment Location<br /><i>Place comments in the default wordpress comments location.</i></th>
                            <td><input type="checkbox" name="enable" value="1" <?php $enabled = get_option('enable'); checked( $enabled, 1 ); ?>/></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Custom Width<br /><i>Manually adjust the commenting section width. Leave blank for default.</i></th>
                            <td><input type="text" name="custom_width" value="<?php echo esc_attr(get_option('custom_width')); ?>"/></td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>
            </div>
        <?php }
    }

    function parler_register_settings()
    {
        register_setting('parler-option-group', 'enable');
    }
}
