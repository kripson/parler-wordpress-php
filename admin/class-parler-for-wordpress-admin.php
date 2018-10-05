<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       https://parler.com
 * @since      1.0.0
 *
 * @package    Parler_For_Wordpress
 * @subpackage Parler_For_Wordpress/admin
 * @author     Joshua Copeland <Josh@RemoteDevForce.com>
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
        if (PARLER_FOR_WORDPRESS_ENV === 'DEV') {
            wp_enqueue_style($this->plugin_name, '/wp-content/plugins/parler/public/css/parler-for-wordpress-public.css', array(), $this->version, 'all');
        } else {
            wp_enqueue_style($this->plugin_name, 'https://plugin.parler.com/production/parler-for-wordpress-public.css', array(), $this->version, 'all');
        }

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

        if (PARLER_FOR_WORDPRESS_ENV === 'DEV') {
            wp_enqueue_script($this->plugin_name, '/wp-content/plugins/parler/public/js/parler-for-wordpress-public.js', array('jquery'), $this->version, false);
        } else {
            wp_enqueue_script($this->plugin_name, 'https://plugin.parler.com/production/parler-for-wordpress-public.js', array('jquery'), $this->version, false);
        }

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/parler-for-wordpress-admin.js', array('jquery'), $this->version, false);


    }

    /**
     * Add admin menu for Parler
     *
     * @param $wp_admin_bar
     * @since    1.0.0
     * @param    WP_Admin_Bar $wp_admin_bar Instance of the WP_Admin_Bar.
     */
    public function parley_add_admin_menu()
    {
        if (!current_user_can('moderate_comments')) {
            return;
        }

        // Remove comments from menu since Parler stores it for you.
        remove_menu_page('edit-comments.php');

        // Add options page for Parler in settings
//        add_options_page('Parler Admin Settings', 'Parler', 'manage_options', 'parler', array(__CLASS__, 'parler_options_page'));
        add_menu_page('Parler Settings', 'Parler', 'manage_options', 'parler', array(__CLASS__, 'parler_options_page'), 'dashicons-testimonial', 24);

    }

    /**
     * Add settings page for Parler
     *
     * @since    1.0.0
     */
    public function parley_register_settings_page()
    {
        if (!current_user_can('moderate_comments')) {
            return;
        }

        // Register Settings

        // Integration Settings
        register_setting('parler-integration-settings', 'parler_temp_token', array('default' => null));
        register_setting('parler-integration-settings', 'parler_api_secret', array('default' => null));

        // Display Settings
        register_setting('parler-display-settings', 'parler_enabled', array('default' => false));
        register_setting('parler-display-settings', 'parler_custom_width', array('default' => '480px'));
    }

    public function parler_options_page()
    {
        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';

        var_dump($_GET);
        if (PARLER_FOR_WORDPRESS_ENV === 'DEV') {
            $ssoUrl = 'http://staging-parler-sso.s3-website-us-west-2.amazonaws.com/?source=';
        } else {
            $ssoUrl = 'https://sso.parler.com/?source=';
        }
        ?>
        <div class="wrap">
            <h1>Parler Settings</h1>
            <h2 class="nav-tab-wrapper">
                <a href="?page=parler&tab=setup"
                   class="nav-tab <?php echo $active_tab == 'setup' ? 'nav-tab-active' : ''; ?>">Setup</a>
                <a href="?page=parler&tab=general"
                   class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">General</a>
                <a href="?page=parler&tab=import"
                   class="nav-tab <?php echo $active_tab == 'import' ? 'nav-tab-active' : ''; ?>">Import</a>
            </h2>
            <?php
            if ($active_tab == 'general') {
                ?>
                <form method="post" action="options.php"><?php
                    settings_fields('parler-display-settings');
                    do_settings_sections('parler-display-settings');
                    ?>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">Default Comment Location<br/>
                                <p style="font-weight: normal;">Place comments in the default wordpress comments
                                    location.</p></th>
                            <td><input type="checkbox" name="parler_enabled" value="1"
                                    <?php checked(get_option('parler_enabled'), 1); ?>/>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Custom Width<br/>
                                <p style="font-weight: normal;">Manually adjust the commenting sections maximum width.
                                    Leave empty for fluid width.</p></th>
                            <td><input type="text" name="parler_custom_width" value="
                            <?php echo esc_attr(get_option('parler_custom_width')); ?>"/>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form> <?php
            } else if ($active_tab == 'setup') {
                ?><h2>Setup Parler</h2>
                <?php if ($parlerApiSecret = get_option('parler_api_secret')) { ?>
                    <p>Integration Completed</p>
                <?php } else { // Integration Incomplete
                    $sourceUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                    ?>

                    <p>To activate the Parler Commenting System, simply click on Login button below.</p>
                    <a href="<?php echo $ssoUrl . urlencode($sourceUrl); ?>">
                        <button>Sign in to Parler</button>
                    </a>
                <?php } ?>
                <div style="max-width: <?php echo get_option('parley_custom_width'); ?>">
                    <div id="comments"></div>
                </div>
                <?php
            } else if ($active_tab == 'import') {

            } else {

            }
            ?>
        </div>
    <?php }
}
